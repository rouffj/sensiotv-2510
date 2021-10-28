<?php

namespace App\Controller;

use App\OmdbApi;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/movie", name="movie_", methods={"GET"})
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/latest", name="latest")
     */
    public function latest(MovieRepository $movieRepository)
    {
        $movies = $movieRepository->findBy([], ['releaseDate' => 'DESC']);

        return $this->render('movie/latest.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * @Route("/{id}/import", name="import", requirements={"id": "tt\d+"})
     */
    public function import(string $id, OmdbApi $omdbApi, EntityManagerInterface $manager): Response
    {
        if (!$this->isGranted('ROLE_MOVIE_IMPORT')) {
            throw new AccessDeniedException('You should have the role ROLE_MOVIE_IMPORT to access this page');
        }
        $movieData = $omdbApi->requestOneById($id);
        $movie = Movie::fromApi($movieData);
        $manager->persist($movie);
        $manager->flush();

        return $this->redirectToRoute('movie_latest');
    }


    /**
     * @Route("/{id}", name="show", requirements={"id": "\d+"}, defaults={"id": 1})
     */
    public function show(int $id, Movie $movie): Response
    {
        //$this->denyAccessUnlessGranted('PROJECT.EDIT', $project);
        $this->denyAccessUnlessGranted('MOVIE_SHOW', $movie, 'You can only watch movies younger than you');

        return $this->render('movie/show.html.twig', [
            'id' => $id,
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request, OmdbApi $omdbApi)
    {
        $keyword = $request->query->get('keyword', 'Harry Potter');
        //$request->request->get('');

        $movies = $omdbApi->requestAllBySearch($keyword);

        dump($movies);

        return $this->render('movie/search.html.twig', [
            'movies' => $movies,
            'keyword' => $keyword,
        ]);
    }
}

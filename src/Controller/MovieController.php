<?php

namespace App\Controller;

use App\OmdbApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie", name="movie_", methods={"GET"})
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/latest", name="latest")
     */
    public function latest()
    {
        return $this->render('movie/latest.html.twig');
    }

    /**
     * @Route("/{id}", name="show", requirements={"id": "\d+"}, defaults={"id": 1})
     */
    public function show(int $id): Response
    {
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

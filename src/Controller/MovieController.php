<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function search()
    {
        return $this->render('movie/search.html.twig');
    }
}

<?php

namespace App\Controller\Front;

use App\Repository\ComicsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComicsController extends AbstractController
{
    /**
     * @Route("/comics", name="comics")
     */
    public function index(): Response
    {
        return $this->render('comics/index.html.twig', [
            'controller_name' => 'ComicsController',
        ]);
    }

    /**
     * @Route("/front/comics", name="comic_list")
     */
    public function productList(ComicsRepository $comicsRepository)
    {
        $comics = $comicsRepository->findAll();

        return $this->render("front/comics.html.twig", ['comics' => $comics]);
    }

    /**
     * @Route("/front/comic/{id}", name="comic_show")
     */
    public function writerShow($id, ComicsRepository $comicsRepository)
    {
        $comic = $comicsRepository->find($id);

        return $this->render("front/comic.html.twig", ['comic' => $comic]);
    }
}

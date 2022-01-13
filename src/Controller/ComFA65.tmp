<?php

namespace App\Controller;

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
}

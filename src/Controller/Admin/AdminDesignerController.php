<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDesignerController extends AbstractController
{
    /**
     * @Route("/admin/designer", name="admin_designer")
     */
    public function index(): Response
    {
        return $this->render('admin_designer/index.html.twig', [
            'controller_name' => 'AdminDesignerController',
        ]);
    }
}

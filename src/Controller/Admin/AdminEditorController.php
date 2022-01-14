<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEditorController extends AbstractController
{
    /**
     * @Route("/admin/editor", name="admin_editor")
     */
    public function index(): Response
    {
        return $this->render('admin_editor/index.html.twig', [
            'controller_name' => 'AdminEditorController',
        ]);
    }
}

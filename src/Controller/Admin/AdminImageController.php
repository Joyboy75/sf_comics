<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminImageController extends AbstractController
{
    /**
     * @Route("/admin/image", name="admin_image")
     */
    public function index(): Response
    {
        return $this->render('admin_image/index.html.twig', [
            'controller_name' => 'AdminImageController',
        ]);
    }

    /**
     * @Route("admin/create/image", name="admin_create_media")
     */
    public function adminCreateMedia(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        SluggerInterface $sluggerInterface
    ) {
        $image = new Image();

        $imageForm = $this->createForm(ImageType::class, $image);

        $imageForm->handleRequest($request);

        if ($imageForm->isSubmitted() && $imageForm->isValid()) {

            // On récupère le fichier
            $mediaFile = $imageForm->get('src')->getData();

            if ($mediaFile) {

                // On créée un nom unique à notre fichier à partir du nom original
                // Pour éviter tout problème de confusion

                // On récupère le nom original du fichier
                $originalFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);

                // On utilise slug sur le nom original pour avoir un nom valide du fichier
                $safeFilename = $sluggerInterface->slug($originalFilename);

                // On ajoute un id unique au nom de l'image
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $mediaFile->guessExtension();

                // On déplace le fichier dans le dossier public/image
                // la destination du fichier est enregistré dans 'images_directory'
                // qui est défini dans le fichier config\services.yaml

                $mediaFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                $image->setSrc($newFilename);
            }

            $entityManagerInterface->persist($image);

            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_comic_list");
        }

        return $this->render("admin/imageform.html.twig", ['imageForm' => $imageForm->createView()]);
    }
}

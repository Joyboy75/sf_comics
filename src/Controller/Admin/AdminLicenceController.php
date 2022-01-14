<?php

namespace App\Controller\Admin;

use App\Entity\Licence;
use App\Form\LicenceType;
use App\Repository\LicenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminLicenceController extends AbstractController
{
    /**
     * @Route("/admin/licence", name="admin_licence")
     */
    public function index(): Response
    {
        return $this->render('admin_licence/index.html.twig', [
            'controller_name' => 'AdminLicenceController',
        ]);
    }

     /**
     * @Route("/admin/licences", name="admin_licence_list")
     */
    public function adminLicenceList(LicenceRepository $licenceRepository)
    {
        $licences = $licenceRepository->findAll();

        return $this->render("/admin/licences.html.twig", ['licences' => $licences]);
    }

    /**
     * @Route("/admin/licence/{id}", name="admin_licence_show")
     */
    public function adminLicenceShow($id, LicenceRepository $licenceRepository)
    {
        $licence = $licenceRepository->find($id);

        return $this->render("/admin/licence.html.twig", ['licence' => $licence]);
    }
    /**
     * @Route("admin/create/licence/", name="admin_create_licence")
     */
    public function adminLicenceCreate(Request $request, EntityManagerInterface $entityManagerInterface, SluggerInterface $sluggerInterface){
        $licence = new Licence();

        $licenceForm = $this->createForm(LicenceType::class, $licence);

        $licenceForm->handleRequest($request);

        if($licenceForm->isSubmitted() && $licenceForm->isValid()){

            // On récupère le fichier que l'on rentre dans le champs du formulaire
            $mediaFile = $licenceForm->get('media')->getData();

            if ($mediaFile) {

                // On crée un nom unique avec le nom original de l'image pour éviter 
                // tout problème lors de l'enregistrement dans le dossier public

                // on récupère le nom original du fichier
                $originalFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);

                // On utilise slug sur le nom original pouur avoir un nom valide
                $safeFilename = $sluggerInterface->slug($originalFilename);

                // On ajoute un id unique au nom du fichier
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $mediaFile->guessExtension();

                // On déplace le fichier dans le dossier public/media
                // la destination est définie dans 'images_directory'
                // du fichier config/services.yaml

                $mediaFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                $licence->setMedia($newFilename);
            }

            
            $entityManagerInterface->persist($licence);
            $entityManagerInterface->flush();
            
            $this->addFlash(
                'notice',
                'Un licence a été créé'
            );

            return $this->redirectToRoute('admin_licence_list');
        }

        return $this->render('admin/licenceForm.html.twig', [ 'licenceForm' => $licenceForm->createView()]);
    
    }

    /**
      * @Route("admin/update/licence/{id}", name="admin_update_licence")
      */
      public function adminLicenceUpdate(
        $id,
         LicenceRepository $licenceRepository,
         Request $request, // class permettant d'utiliser le formulaire de récupérer les information 
         EntityManagerInterface $entityManagerInterface, // class permettantd'enregistrer ds la bdd
         SluggerInterface $sluggerInterface
         ){
             $licence = $licenceRepository->find($id);

             // Création du formulaire
          $licenceForm = $this->createForm(LicenceType::class, $licence);

          // Utilisation de handleRequest pour demander au formulaire de traiter les informations
      // rentrées dans le formulaire
      // Utilisation de request pour récupérer les informations rentrées dans le formualire
          $licenceForm->handleRequest($request);


          if($licenceForm->isSubmitted() && $licenceForm->isValid())
          {   

            // On récupère le fichier que l'on rentre dans le champs du formulaire
            $mediaFile = $licenceForm->get('media')->getData();

            if ($mediaFile) {

                // On crée un nom unique avec le nom original de l'image pour éviter 
                // tout problème lors de l'enregistrement dans le dossier public

                // on récupère le nom original du fichier
                $originalFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);

                // On utilise slug sur le nom original pouur avoir un nom valide
                $safeFilename = $sluggerInterface->slug($originalFilename);

                // On ajoute un id unique au nom du fichier
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $mediaFile->guessExtension();

                // On déplace le fichier dans le dossier public/media
                // la destination est définie dans 'images_directory'
                // du fichier config/services.yaml

                $mediaFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                $licence->setMedia($newFilename);
            }
              // persist prépare l'enregistrement ds la bdd analyse le changement à faire
              $entityManagerInterface->persist($licence);
              $id = $licenceRepository->find($id);

              // flush enregistre dans la bdd
              $entityManagerInterface->flush();

              $this->addFlash(
                'notice',
                'La licence a bien été modifié !'
            );

              return $this->redirectToRoute('admin_licence_list');

          }

          return $this->render('admin/licenceform.html.twig', ['licenceForm'=> $licenceForm->createView()]);
    }
    /**
     * @Route("admin/delete/licence/{id}", name="admin_delete_licence")
     */
    public function adminLicenceDelete(
        $id,
        LicenceRepository $licenceRepository,
        EntityManagerInterface $entityManagerInterface
        ){

            $licence = $licenceRepository->find($id);

            //remove supprime le licence et flush enregistre ds la bdd
            $entityManagerInterface->remove($licence);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'Votre licence a bien été supprimé'
            );

            return $this->redirectToRoute('admin_licence_list');

    }
}



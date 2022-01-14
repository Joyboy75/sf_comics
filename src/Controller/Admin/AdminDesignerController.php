<?php

namespace App\Controller\Admin;

use App\Entity\Designer;
use App\Form\DesignerType;
use App\Repository\DesignerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

     /**
     * @Route("/admin/designers", name="admin_designer_list")
     */
    public function adminDesignerList(DesignerRepository $designerRepository)
    {
        $designers = $designerRepository->findAll();

        return $this->render("/admin/designers.html.twig", ['designers' => $designers]);
    }

    /**
     * @Route("/admin/designer/{id}", name="admin_designer_show")
     */
    public function adminDesignerShow($id, DesignerRepository $designerRepository)
    {
        $designer = $designerRepository->find($id);

        return $this->render("/admin/designer.html.twig", ['designer' => $designer]);
    }
    /**
     * @Route("admin/create/designer/", name="admin_create_designer")
     */
    public function adminDesignerCreate(Request $request, EntityManagerInterface $entityManagerInterface){
        $designer = new Designer();

        $designerForm = $this->createForm(DesignerType::class, $designer);

        $designerForm->handleRequest($request);

        if($designerForm->isSubmitted() && $designerForm->isValid()){
            $entityManagerInterface->persist($designer);
            $entityManagerInterface->flush();
            
            $this->addFlash(
                'notice',
                'Un designer a été créé'
            );

            return $this->redirectToRoute('admin_designer_list');
        }

        return $this->render('admin/designerform.html.twig', [ 'designerForm' => $designerForm->createView()]);
    
    }

    /**
      * @Route("admin/update/designer/{id}", name="admin_update_designer")
      */
      public function adminDesignerUpdate(
        $id,
         DesignerRepository $designerRepository,
         Request $request, // class permettant d'utiliser le formulaire de récupérer les information 
         EntityManagerInterface $entityManagerInterface // class permettantd'enregistrer ds la bdd
         ){
             $designer = $designerRepository->find($id);

             // Création du formulaire
          $designerForm = $this->createForm(DesignerType::class, $designer);

          // Utilisation de handleRequest pour demander au formulaire de traiter les informations
      // rentrées dans le formulaire
      // Utilisation de request pour récupérer les informations rentrées dans le formualire
          $designerForm->handleRequest($request);


          if($designerForm->isSubmitted() && $designerForm->isValid())
          {   
              // persist prépare l'enregistrement ds la bdd analyse le changement à faire
              $entityManagerInterface->persist($designer);
              $id = $designerRepository->find($id);

              // flush enregistre dans la bdd
              $entityManagerInterface->flush();

              $this->addFlash(
                'notice',
                'Le designer a bien été modifié !'
            );

              return $this->redirectToRoute('admin_designer_list');

          }

          return $this->render('admin/designerform.html.twig', ['designerForm'=> $designerForm->createView()]);
    }
    /**
     * @Route("admin/delete/designer/{id}", name="admin_delete_designer")
     */
    public function adminDesignerDelete(
        $id,
        DesignerRepository $designerRepository,
        EntityManagerInterface $entityManagerInterface
        ){

            $designer = $designerRepository->find($id);

            //remove supprime le designer et flush enregistre ds la bdd
            $entityManagerInterface->remove($designer);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'Votre designer a bien été supprimé'
            );

            return $this->redirectToRoute('admin_designer_list');

    }
}



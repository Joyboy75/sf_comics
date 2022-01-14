<?php

namespace App\Controller\Admin;

use App\Entity\Editor;
use App\Form\EditorType;
use App\Repository\EditorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

     /**
     * @Route("/admin/editors", name="admin_editor_list")
     */
    public function adminEditorList(EditorRepository $editorRepository)
    {
        $editors = $editorRepository->findAll();

        return $this->render("/admin/editors.html.twig", ['editors' => $editors]);
    }

    /**
     * @Route("/admin/editor/{id}", name="admin_editor_show")
     */
    public function adminEditorShow($id, EditorRepository $editorRepository)
    {
        $editor = $editorRepository->find($id);

        return $this->render("/admin/editor.html.twig", ['editor' => $editor]);
    }
    /**
     * @Route("admin/create/editor/", name="admin_create_editor")
     */
    public function adminEditorCreate(Request $request, EntityManagerInterface $entityManagerInterface){
        $editor = new Editor();

        $editorForm = $this->createForm(EditorType::class, $editor);

        $editorForm->handleRequest($request);

        if($editorForm->isSubmitted() && $editorForm->isValid()){
            $entityManagerInterface->persist($editor);
            $entityManagerInterface->flush();
            
            $this->addFlash(
                'notice',
                'Un editor a été créé'
            );

            return $this->redirectToRoute('admin_editor_list');
        }

        return $this->render('admin/editorform.html.twig', [ 'editorForm' => $editorForm->createView()]);
    
    }

    /**
      * @Route("admin/update/editor/{id}", name="admin_update_editor")
      */
      public function admineditorUpdate(
        $id,
         EditorRepository $editorRepository,
         Request $request, // class permettant d'utiliser le formulaire de récupérer les information 
         EntityManagerInterface $entityManagerInterface // class permettantd'enregistrer ds la bdd
         ){
             $editor = $editorRepository->find($id);

             // Création du formulaire
          $editorForm = $this->createForm(EditorType::class, $editor);

          // Utilisation de handleRequest pour demander au formulaire de traiter les informations
      // rentrées dans le formulaire
      // Utilisation de request pour récupérer les informations rentrées dans le formualire
          $editorForm->handleRequest($request);


          if($editorForm->isSubmitted() && $editorForm->isValid())
          {   
              // persist prépare l'enregistrement ds la bdd analyse le changement à faire
              $entityManagerInterface->persist($editor);
              $id = $editorRepository->find($id);

              // flush enregistre dans la bdd
              $entityManagerInterface->flush();

              $this->addFlash(
                'notice',
                'L\' editor a bien été modifié !'
            );

              return $this->redirectToRoute('admin_editor_list');

          }

          return $this->render('admin/editorform.html.twig', ['editorForm'=> $editorForm->createView()]);
    }
    /**
     * @Route("admin/delete/editor/{id}", name="admin_delete_editor")
     */
    public function adminEditorDelete(
        $id,
        EditorRepository $editorRepository,
        EntityManagerInterface $entityManagerInterface
        ){

            $editor = $editorRepository->find($id);

            //remove supprime le editor et flush enregistre ds la bdd
            $entityManagerInterface->remove($editor);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'Votre editor a bien été supprimé'
            );

            return $this->redirectToRoute('admin_editor_list');

    }
}



<?php

namespace App\Controller\Admin;

use App\Entity\Writer;
use App\Form\WriterType;
use App\Repository\WriterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminWriterController extends AbstractController
{
    /**
     * @Route("/admin/writer", name="admin_writer")
     */
    public function index(): Response
    {
        return $this->render('admin_writer/index.html.twig', [
            'controller_name' => 'AdminWriterController',
        ]);
    }
     
    /**
     * @Route("/admin/writers", name="admin_writer_list")
     */
    public function adminWriterList(WriterRepository $writerRepository)
    {
        $writers = $writerRepository->findAll();

        return $this->render("/admin/writers.html.twig", ['writers' => $writers]);
    }

    /**
     * @Route("/admin/writer/{id}", name="admin_writer_show")
     */
    public function adminWriterShow($id, WriterRepository $writerRepository)
    {
        $writer = $writerRepository->find($id);

        return $this->render("/admin/writer.html.twig", ['writer' => $writer]);
    }
    
    /**
     * @Route("admin/create/writer/", name="admin_create_writer")
     */
    public function adminWriterCreate(Request $request, EntityManagerInterface $entityManagerInterface){
        $writer = new Writer();

        $writerForm = $this->createForm(WriterType::class, $writer);

        $writerForm->handleRequest($request);

        if($writerForm->isSubmitted() && $writerForm->isValid()){
            $entityManagerInterface->persist($writer);
            $entityManagerInterface->flush();
            
            $this->addFlash(
                'notice',
                'Un writer a été créé'
            );

            return $this->redirectToRoute('admin_writer_list');
        }

        return $this->render('admin/writerform.html.twig', [ 'writerForm' => $writerForm->createView()]);
    
    }

    /**
      * @Route("admin/update/writer/{id}", name="admin_update_writer")
      */
      public function adminWriterUpdate(
        $id,
         WriterRepository $writerRepository,
         Request $request, // class permettant d'utiliser le formulaire de récupérer les information 
         EntityManagerInterface $entityManagerInterface // class permettantd'enregistrer ds la bdd
         ){
             $writer = $writerRepository->find($id);

             // Création du formulaire
          $writerForm = $this->createForm(WriterType::class, $writer);

          // Utilisation de handleRequest pour demander au formulaire de traiter les informations
      // rentrées dans le formulaire
      // Utilisation de request pour récupérer les informations rentrées dans le formualire
          $writerForm->handleRequest($request);


          if($writerForm->isSubmitted() && $writerForm->isValid())
          {   
              // persist prépare l'enregistrement ds la bdd analyse le changement à faire
              $entityManagerInterface->persist($writer);
              $id = $writerRepository->find($id);

              // flush enregistre dans la bdd
              $entityManagerInterface->flush();

              $this->addFlash(
                'notice',
                'Le writer a bien été modifié !'
            );

              return $this->redirectToRoute('admin_writer_list');

          }

          return $this->render('admin/writerform.html.twig', ['writerForm'=> $writerForm->createView()]);
    }
    /**
     * @Route("admin/delete/writer/{id}", name="admin_delete_writer")
     */
    public function adminWriterDelete(
        $id,
        WriterRepository $writerRepository,
        EntityManagerInterface $entityManagerInterface
        ){

            $writer = $writerRepository->find($id);

            //remove supprime le writer et flush enregistre ds la bdd
            $entityManagerInterface->remove($writer);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'Votre writer a bien été supprimé'
            );

            return $this->redirectToRoute('admin_writer_list');

    }
}



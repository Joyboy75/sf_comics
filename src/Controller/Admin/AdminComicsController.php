<?php

namespace App\Controller\Admin;

use App\Entity\Comics;
use App\Form\ComicsType;
use App\Repository\ComicsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminComicsController extends AbstractController
{
    /**
     * @Route("/comics", name="admin_comics")
     */
    public function index(): Response
    {
        return $this->render('comics/index.html.twig', [
            'controller_name' => 'AdminComicsController',
        ]);
    }

    /**
     * @Route("/admin/comics", name="admin_comic_list")
     */
    public function adminComicList(ComicsRepository $comicsRepository)
    {
        $comics = $comicsRepository->findAll();

        return $this->render("/admin/comics.html.twig", ['comics' => $comics]);
    }

    /**
     * @Route("/admin/comic/{id}", name="admin_comic_show")
     */
    public function adminComicShow($id, ComicsRepository $comicsRepository)
    {
        $comic = $comicsRepository->find($id);

        return $this->render("/admin/comic.html.twig", ['comic' => $comic]);
    }
    /**
     * @Route("admin/create/comic/", name="admin_create_comic")
     */
    public function adminComicCreate(Request $request, EntityManagerInterface $entityManagerInterface){
        $comic = new Comics();

        $comicsForm = $this->createForm(ComicsType::class, $comic);

        $comicsForm->handleRequest($request);

        if($comicsForm->isSubmitted() && $comicsForm->isValid()){
            $entityManagerInterface->persist($comic);
            $entityManagerInterface->flush();
            
            $this->addFlash(
                'notice',
                'Un comic a été créé'
            );

            return $this->redirectToRoute('admin_comic_list');
        }

        return $this->render('admin/comicsForm.html.twig', [ 'comicsForm' => $comicsForm->createView()]);
    
    }

    /**
      * @Route("admin/update/comic/{id}", name="admin_update_comic")
      */
      public function adminComicUpdate(
        $id,
         ComicsRepository $comicsRepository,
         Request $request, // class permettant d'utiliser le formulaire de récupérer les information 
         EntityManagerInterface $entityManagerInterface // class permettantd'enregistrer ds la bdd
         ){
             $comic = $comicsRepository->find($id);

             // Création du formulaire
          $comicsForm = $this->createForm(ComicsType::class, $comic);

          // Utilisation de handleRequest pour demander au formulaire de traiter les informations
      // rentrées dans le formulaire
      // Utilisation de request pour récupérer les informations rentrées dans le formualire
          $comicsForm->handleRequest($request);


          if($comicsForm->isSubmitted() && $comicsForm->isValid())
          {   
              // persist prépare l'enregistrement ds la bdd analyse le changement à faire
              $entityManagerInterface->persist($comic);
              $id = $comicsRepository->find($id);

              // flush enregistre dans la bdd
              $entityManagerInterface->flush();

              $this->addFlash(
                'notice',
                'Le comic a bien été modifié !'
            );

              return $this->redirectToRoute('admin_comic_list');

          }

          return $this->render('admin/comicsform.html.twig', ['comicsForm'=> $comicsForm->createView()]);
    }
    /**
     * @Route("admin/delete/comic/{id}", name="admin_delete_comic")
     */
    public function adminComicDelete(
        $id,
        ComicsRepository $comicsRepository,
        EntityManagerInterface $entityManagerInterface
        ){

            $comic = $comicsRepository->find($id);

            //remove supprime le comic et flush enregistre ds la bdd
            $entityManagerInterface->remove($comic);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'Votre comic a bien été supprimé'
            );

            return $this->redirectToRoute('admin_comic_list');

    }
}

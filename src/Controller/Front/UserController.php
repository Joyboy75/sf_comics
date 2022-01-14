<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController{

    /**
     * @Route("user/create/", name="user_create")
     */
    public function createUser(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        UserPasswordHasherInterface $userPasswordHasherInterface) {

        $user = new User();

        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);

        if($userForm->isSubmitted() && $userForm->isValid()){

            $user->setRoles(["ROLE_USER"]);

            // On récupère le mdp entré dans le formulaire
            $plainPassword = $userForm->get('password')->getData();

            // On hasch le mdp pour le sécuriser
            $hashedPassword = $userPasswordHasherInterface->hashPassword($user, $plainPassword);

            $user->setPassword($hashedPassword);

            $entityManagerInterface->persist($user);
            
            $entityManagerInterface->flush();

            return $this->redirectToRoute('comic_list');


        }


        return $this->render("front/userform.html.twig", [ 'userForm' => $userForm->createView()]);

    }


    // /**
    //  * @Route("/admin/update/user/{id}", name="admin_update_user")
    //  */
    // public function updateUser($id,
    //     Request $request,
    //     EntityManagerInterface $entityManagerInterface,
    //     UserPasswordHasherInterface $userPasswordHasherInterface,
    //     UserRepository $userRepository) {

    //     $user = $userRepository->find($id);

    //     $userForm = $this->createForm(UserType::class, $user);

    //     $userForm->handleRequest($request);

    //     if($userForm->isSubmitted() && $userForm->isValid()){

    //         $user->setRoles(["ROLE_USER"]);

    //         // On récupère le mdp entré dans le formulaire
    //         $plainPassword = $userForm->get('password')->getData();

    //         // On hasch le mdp pour le sécuriser
    //         $hashedPassword = $userPasswordHasherInterface->hashPassword($user, $plainPassword);

    //         $user->setPassword($hashedPassword);

    //         $entityManagerInterface->persist($user);
            
    //         $entityManagerInterface->flush();

    //         return $this->redirectToRoute('comic_list');


    //     }

    //     return $this->render("front/userform.html.twig", ['userForm' => $userForm->createView()]);

    // }

}
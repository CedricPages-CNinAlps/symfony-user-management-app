<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserType;

final class UserController extends AbstractController
{

    #[Route('/users', name: 'users_list')]
    public function listUsers(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/users/edit/{id}', name: 'users_edit', methods: ['GET', 'POST'])]
    public function editUser(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTime());
            $em->flush();

            return $this->redirectToRoute('users_list');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/users/new', name: 'user_create')]
    public function createUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User(); // Nouvel utilisateur
        $form = $this->createForm(UserType::class, $user); // Réutilisation du même formulaire

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCreatedAt(new \DateTime()); // Définir la date de création
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('users_list'); // Redirection après création
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}

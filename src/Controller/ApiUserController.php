<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

final class ApiUserController extends AbstractController
{
//    #[Route('/api/users', name: 'app_api_user')]
//    public function index(): Response
//    {
//        return $this->render('api_user/index.html.twi
//', [
//            'controller_name' => 'ApiUserController',
//        ]);
//    }

    #[Route('/api/users', name: 'api_users', methods: ['GET'])]
    public function listUsers(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        return $this->json($users);
    }

    #[Route('/api/users', name: 'api_create_user', methods: ['POST'])]
    public function createUser(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['firstname'], $data['lastname'], $data['email'], $data['groupe'])) {
            return $this->json(['error' => 'Données incomplètes'], 400);
        }

        $user = new User();
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setEmail($data['email']);
        $user->setGroupe($data['groupe']);
        $user->setCreatedAt(new \DateTime());

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(['message' => 'Utilisateur créé avec succès', 'id' => $user->getId()], 201);
    }

    #[Route('/api/users/{id}', name: 'api_update_user', methods: ['PUT'])]
    public function updateUser(User $user, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user->setLastname($data['lastname'] ?? $user->getLastname());
        $user->setFirstname($data['firstname'] ?? $user->getFirstname());
        $user->setEmail($data['email'] ?? $user->getEmail());
        $user->setGroupe($data['groupe'] ?? $user->getGroupe());
        $user->setUpdatedAt(new \DateTime());
        $em->flush();

        return $this->json($user);
    }

//    #[Route('/api/users/{id}', name: 'api_delete_user', methods: ['DELETE'])]
    #[Route('/api/users/{id}', name: 'api_delete_user', methods: ['POST'])]
    public function deleteUser(Request $request,User $user, EntityManagerInterface $em, CsrfTokenManagerInterface $csrfTokenManager): JsonResponse
    {
        $token = new CsrfToken('delete' . $user->getId(), $request->request->get('_token'));
        if (!$csrfTokenManager->isTokenValid($token)) {
            return $this->json(['message' => 'Token CSRF invalide'], Response::HTTP_FORBIDDEN);
        }

        $em->remove($user);
        $em->flush();

        return $this->json(['message' => 'Utilisateur supprimé'], Response::HTTP_OK);
    }

}

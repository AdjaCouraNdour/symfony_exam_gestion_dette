<?php
// src/Controller/UserController.php

namespace App\Controller;

use App\Entity\User;
use App\Enums\UserRole;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface; // Importez cette classe
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager; // Déclarez la propriété

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager; 

    }

    #[Route('api/users', name: 'api_users_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        
        $result = array_map(function (User $user) {
            return [
                'id' => $user->getId(),
                'login' => $user->getLogin(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'role' => $user->getRole()->name,
            ];
        }, $users);

        return $this->json($result);
    }

    #[Route('api/users/search', name: 'api_users_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $login = $request->query->get('login');
        if (!$login) {
            return $this->json(['error' => 'Le champ login est requis'], 400);
        }

        $user = $this->userRepository->findOneBy(['login' => $login]);
        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        return $this->json([
            'id' => $user->getId(),
            'login' => $user->getLogin(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'role' => $user->getRole()->name,
        ]);
    }

    #[Route('api/users', name: 'api_users_create', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['login'], $data['nom'], $data['prenom'], $data['password'], $data['role'])) {
            return $this->json(['error' => 'Données manquantes'], 400);
        }

        $user = new User();
        $user->setLogin($data['login']);
        $user->setNom($data['nom']);
        $user->setPrenom($data['prenom']);
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        $user->setRole(UserRole::from($data['role']));

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->json(['error' => 'Données invalides'], 400);
        }

        // Utilisez maintenant l'EntityManagerInterface pour persister et flush les données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json(['message' => 'Utilisateur créé avec succès'], 201);
    }

    #[Route('api/users/{id}', name: 'api_users_edit', methods: ['PATCH'])]
    public function edit(Request $request, int $id, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $this->userRepository->find($id);
        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        if (isset($data['login'])) {
            $user->setLogin($data['login']);
        }
        if (isset($data['nom'])) {
            $user->setNom($data['nom']);
        }
        if (isset($data['prenom'])) {
            $user->setPrenom($data['prenom']);
        }
        if (isset($data['role'])) {
            $role = UserRole::tryFrom($data['role']);
            if (!$role) {
                return $this->json(['error' => 'Rôle invalide'], 400);
            }
            $user->setRole($role);
        }

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        try {
            $this->userRepository->save($user);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la mise à jour de l\'utilisateur : ' . $e->getMessage()], 500);
        }

        return $this->json(['message' => 'Utilisateur mis à jour avec succès']);
    }

    #[Route('api/users/{id}', name: 'api_users_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return $this->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        try {
            $this->userRepository->remove($user);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la suppression de l\'utilisateur : ' . $e->getMessage()], 500);
        }

        return $this->json(['message' => 'Utilisateur supprimé avec succès']);
    }
}

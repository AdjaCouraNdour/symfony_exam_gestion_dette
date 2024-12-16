<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Entity\Client;
use App\Entity\User;
use App\Enums\UserRole;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface; // Importez cette classe
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    private ClientRepository $clientRepository;
    private UserRepository $userRepository;

    public function __construct(
        ClientRepository $clientRepository,
        UserRepository $userRepository
    ) {
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('api/clients', name: 'api_clients_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $clients = $this->clientRepository->findAll();
        $result = array_map(function (Client $client) {
            return [
                'id' => $client->getId(),
                'surname' => $client->getSurname(),
                'telephone' => $client->getTelephone(),
                'adresse' => $client->getAdresse(),
            ];
        }, $clients);

        return $this->json($result);
    }
    
    #[Route('/api/clients/{clientNumber}/dettes', name:'api_clients_dettes', methods:['GET'])]  
    public function getDettesForClient($clientNumber)
    {
        // Récupérer le client à partir du clientNumber
        $client = $this->clientRepository->findOneBy(['telephone' => $clientNumber]);

        if (!$client) {
            return $this->json(['error' => 'Client non trouvé'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer les dettes du client
        $dettes = $client->getDettes();

        return $this->json($dettes);
    }


    #[Route('api/clients/search', name: 'api_clients_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $phone = $request->query->get('phone');
        if (!$phone) {
            return $this->json(['error' => 'Phone number is required'], 400);
        }

        $client = $this->clientRepository->findClientByPhone($phone);
        if (!$client) {
            return $this->json(['error' => 'Client not found'], 404);
        }

        return $this->json([
            'id' => $client->getId(),
            'surname' => $client->getSurname(),
            'telephone' => $client->getTelephone(),
            'adresse' => $client->getAdresse(),
        ]);
    }

    #[Route('api/clients', name: 'api_clients_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validation des champs obligatoires pour le client
        if (empty($data['surname']) || empty($data['telephone']) || empty($data['adresse'])) {
            return $this->json(['error' => 'Les champs surname, telephone et adresse sont requis'], 400);
        }

        $client = new Client();
        $client->setSurname($data['surname']);
        $client->setTelephone($data['telephone']);
        $client->setAdresse($data['adresse']);

        // Gestion de l'ajout d'un utilisateur associé
        if (!empty($data['addUser']) && $data['addUser'] === true) {
            if (empty($data['user']['login']) || empty($data['user']['nom']) || empty($data['user']['prenom']) || empty($data['user']['password'])) {
                return $this->json(['error' => 'Les champs login, nom, prenom et password sont requis pour ajouter un utilisateur'], 400);
            }

            $user = new User();
            $user->setLogin($data['user']['login']);
            $user->setNom($data['user']['nom']);
            $user->setPrenom($data['user']['prenom']);
            $user->setPassword(password_hash($data['user']['password'], PASSWORD_BCRYPT));
            $user->setRole(UserRole::roleClient);

            try {
                $this->userRepository->save($user);
            } catch (\Exception $e) {
                return $this->json(['error' => 'Erreur lors de la création de l\'utilisateur : ' . $e->getMessage()], 500);
            }

            // Associer l'utilisateur au client
            $client->setUserr($user);
        }

        try {
            $this->clientRepository->save($client);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la création du client : ' . $e->getMessage()], 500);
        }

        return $this->json(['message' => 'Client créé avec succès'], 201);
    }

    #[Route('api/clients/edit/{id}', name: 'api_clients_edit', methods: ['PATCH'])]
    public function edit(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $client = $this->clientRepository->find($id);
        if (!$client) {
            return $this->json(['error' => 'Client not found'], 404);
        }

        if (isset($data['surname'])) {
            $client->setSurname($data['surname']);
        }
        if (isset($data['telephone'])) {
            $client->setTelephone($data['telephone']);
        }
        if (isset($data['adresse'])) {
            $client->setAdresse($data['adresse']);
        }

        try {
            $this->clientRepository->save($client);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la mise à jour du client : ' . $e->getMessage()], 500);
        }

        return $this->json(['message' => 'Client updated successfully']);
    }
}






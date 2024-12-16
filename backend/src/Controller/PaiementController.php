<?php

namespace App\Controller;

use App\Entity\Paiement;
use App\Repository\PaiementRepository;
use App\Repository\ArticleRepository;
use App\Repository\DetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PaiementController extends AbstractController
{
    private PaiementRepository $paiementRepository;
    private ArticleRepository $articleRepository;
    private DetteRepository $detteRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        PaiementRepository $paiementRepository,
        ArticleRepository $articleRepository,
        DetteRepository $detteRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->paiementRepository = $paiementRepository;
        $this->articleRepository = $articleRepository;
        $this->detteRepository = $detteRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('api/paiements', name: 'api_paiement_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $paiements = $this->paiementRepository->findAll();

        $result = array_map(function (Paiement $paiement) {
            return [
                'id' => $paiement->getId(),
                'montant' => $paiement->getMontant(),
                'dette' => [
                    'id' => $paiement->getDette()->getId(),
                    'montant' => $paiement->getDette()->getMontant(),
                    'client' => [
                        'id' => $paiement->getDette()->getClient()->getId(),
                        'surname' => $paiement->getDette()->getClient()->getSurname(),
                    ],
                ],
            ];
        }, $paiements);

        return $this->json($result);
    }

    #[Route('api/paiement/{id}', name: 'api_paiement_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $paiement = $this->paiementRepository->find($id);
        if (!$paiement) {
            return $this->json(['error' => 'Paiement non trouvé'], 404);
        }

        return $this->json([
            'id' => $paiement->getId(),
            'montant' => $paiement->getMontant(),
            'dette' => [
                'id' => $paiement->getDette()->getId(),
                'montant' => $paiement->getDette()->getMontant(),
                'client' => [
                    'id' => $paiement->getDette()->getClient()->getId(),
                    'surname' => $paiement->getDette()->getClient()->getSurname(),
                ],
            ],
        ]);
    }

    // Dans votre contrôleur
    // #[Route('/api/clients/{clientNumber}/dettes', name:'api_clients_dettes', methods:['GET'])]  
    // public function getDettesForClient($clientNumber)
    // {
    //     // Récupérer le client à partir du clientNumber
    //     $client = $this->clientRepository->findOneBy(['telephone' => $clientNumber]);

    //     if (!$client) {
    //         return $this->json(['error' => 'Client non trouvé'], Response::HTTP_NOT_FOUND);
    //     }

    //     // Récupérer les dettes du client
    //     $dettes = $client->getDettes();

    //     return $this->json($dettes);
    // }


    #[Route('api/paiement', name: 'api_paiement_create', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['montant'], $data['detteId'])) {
            return $this->json(['error' => 'Données manquantes'], 400);
        }

        $dette = $this->detteRepository->find($data['detteId']);

        if (!$dette) {
            return $this->json(['error' => 'Dette non trouvée'], 404);
        }

        $paiement = new Paiement();
        $paiement->setMontant($data['montant']);
        $paiement->setDette($dette);

        $errors = $validator->validate($paiement);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return $this->json(['errors' => $errorMessages], 400);
        }

        $this->entityManager->persist($paiement);
        $this->entityManager->flush();

        return $this->json(['message' => 'Paiement créé avec succès'], 201);
    }

    #[Route('api/paiement/{id}', name: 'api_paiement_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $paiement = $this->paiementRepository->find($id);
        if (!$paiement) {
            return $this->json(['error' => 'Paiement non trouvé'], 404);
        }

        try {
            $this->entityManager->remove($paiement);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()], 500);
        }

        return $this->json(['message' => 'Paiement supprimé avec succès']);
    }
}

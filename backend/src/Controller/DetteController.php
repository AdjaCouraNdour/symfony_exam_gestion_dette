<?php

namespace App\Controller;

use App\Entity\Dette;
use App\Entity\Client;
use App\Entity\Details;
use App\Enums\EtatDette;
use App\Enums\TypeDette;
use App\Repository\ArticleRepository;
use App\Repository\ClientRepository;
use App\Repository\DetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface; // Importez cette classe
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DetteController extends AbstractController
{
    private DetteRepository $detteRepository;
    private ClientRepository $clientRepository;
    private ArticleRepository $articleRepository;

    public function __construct(DetteRepository $detteRepository, ClientRepository $clientRepository, ArticleRepository $articleRepository)
    {
        $this->detteRepository = $detteRepository;
        $this->clientRepository = $clientRepository;
        $this->articleRepository = $articleRepository;
    }

    #[Route('api/dettes', name: 'api_dettes_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $dettes = $this->detteRepository->findAll();
        $result = array_map(function (Dette $dette) {
            return [
                'id' => $dette->getId(),
                'montant' => $dette->getMontant(),
                'montantVerse' => $dette->getMontantVerse(),
                'montantRestant' => $dette->getMontantRestant(),
                'type' => $dette->getType()->value, // Assuming enum TypeDette has a value property
                'etat' => $dette->getEtat()->value, // Assuming enum EtatDette has a value property
                'client' => [
                    'id' => $dette->getClient()->getId(),
                    'surname' => $dette->getClient()->getSurname(),
                ],
                'createAt' => $dette->getCreateAt()->format('Y-m-d H:i:s'),
                'updateAt' => $dette->getUpdateAt()->format('Y-m-d H:i:s'),
            ];
        }, $dettes);

        return $this->json($result);
    }

    #[Route('api/dettes/{id}', name: 'api_dettes_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $dette = $this->detteRepository->find($id);

        if (!$dette) {
            return $this->json(['error' => 'Dette not found'], 404);
        }

        return $this->json([
            'id' => $dette->getId(),
            'montant' => $dette->getMontant(),
            'montantVerse' => $dette->getMontantVerse(),
            'montantRestant' => $dette->getMontantRestant(),
            'type' => $dette->getType()->value,
            'etat' => $dette->getEtat()->value,
            'client' => [
                'id' => $dette->getClient()->getId(),
                'surname' => $dette->getClient()->getSurname(),
            ],
            'createAt' => $dette->getCreateAt()->format('Y-m-d H:i:s'),
            'updateAt' => $dette->getUpdateAt()->format('Y-m-d H:i:s'),
        ]);
    }


    #[Route('api/dettes', name: 'api_dettes_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        // Validation des champs requis
        if (empty($data['clientnum']) || empty($data['articles']) || !is_array($data['articles'])) {
            return $this->json(['error' => 'Les champs clientnum et articles sont requis'], 400);
        }
    
        // Recherche du client par numéro
        $client = $this->clientRepository->findClientByPhone($data['clientnum']);
        if (!$client) {
            return $this->json(['error' => 'Client non trouvé'], 404);
        }
    
        // Création d'une nouvelle dette
        $dette = new Dette();
        $dette->setClient($client);
        $dette->setType(TypeDette::nonSolde);
        $dette->setEtat(EtatDette::enCours); 
        $dette->setMontantVerse(0);
        $dette->setCreateAt(new \DateTimeImmutable());
        $dette->setUpdateAt(new \DateTimeImmutable());
    
        $montantTotal = 0;
    
        // Traitement des articles
        foreach ($data['articles'] as $articleData) {
            if (empty($articleData['id']) || empty($articleData['quantity'])) {
                return $this->json(['error' => 'Chaque article doit avoir un id et une quantité'], 400);
            }
    
            $article = $this->articleRepository->find($articleData['id']);
            if (!$article) {
                return $this->json(['error' => "Article avec l'id {$articleData['id']} non trouvé"], 404);
            }
    
            $quantite = (int)$articleData['quantity'];
            $montantTotal += $quantite * $article->getPrix();
    
            // Création des détails de la dette
            $details = new Details();
            $details->setArticle($article);
            $details->setDette($dette);
            $details->setQteDette($quantite);
    
            // Ajout des détails à la dette
            $dette->addDetail($details);
        }
    
        $dette->setMontant($montantTotal);
        $dette->setMontantRestant($montantTotal);
    
        try {
            $this->detteRepository->save($dette);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la création de la dette : ' . $e->getMessage()], 500);
        }
    
        return $this->json(['message' => 'Dette créée avec succès'], 201);
    }
    





    #[Route('api/dettes/{id}', name: 'api_dettes_edit', methods: ['PATCH'])]
    public function edit(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $dette = $this->detteRepository->find($id);
        if (!$dette) {
            return $this->json(['error' => 'Dette not found'], 404);
        }

        if (isset($data['montant'])) {
            $dette->setMontant($data['montant']);
        }
        if (isset($data['montantVerse'])) {
            $dette->setMontantVerse($data['montantVerse']);
        }
        if (isset($data['montantRestant'])) {
            $dette->setMontantRestant($data['montantRestant']);
        }
        if (isset($data['type'])) {
            $dette->setType($data['type']);
        }
        if (isset($data['etat'])) {
            $dette->setEtat($data['etat']);
        }

        try {
            $this->detteRepository->save($dette);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la mise à jour de la dette : ' . $e->getMessage()], 500);
        }

        return $this->json(['message' => 'Dette mise à jour avec succès']);
    }
}

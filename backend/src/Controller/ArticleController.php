<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Enums\EtatArticle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;
    private ValidatorInterface $validator;

    public function __construct(ArticleRepository $articleRepository, ValidatorInterface $validator)
    {
        $this->articleRepository = $articleRepository;
        $this->validator = $validator;
    }

    // Liste des articles
    #[Route('/api/articles', name: 'api_articles_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $articles = $this->articleRepository->findAll();
        $result = array_map(function (Article $article) {
            return [
                'id' => $article->getId(),
                'reference' => $article->getReference(),
                'libelle' => $article->getLibelle(),
                'qte_stock' => $article->getQteStock(),
                'prix' => $article->getPrix(),
                'etat' => $article->getEtat()->name,
                'createAt' => $article->getCreateAt()->format('Y-m-d H:i:s'),
                'updateAt' => $article->getUpdateAt()->format('Y-m-d H:i:s'),
            ];
        }, $articles);

        return $this->json($result);
    }

    // Afficher un article par son ID
    #[Route('/api/articles/{id}', name: 'api_articles_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $article = $this->articleRepository->findArticleById($id);

        if (!$article) {
            return $this->json(['error' => 'Article not found'], 404);
        }

        return $this->json([
            'id' => $article->getId(),
            'reference' => $article->getReference(),
            'libelle' => $article->getLibelle(),
            'qte_stock' => $article->getQteStock(),
            'prix' => $article->getPrix(),
            'etat' => $article->getEtat()->name,
            'createAt' => $article->getCreateAt()->format('Y-m-d H:i:s'),
            'updateAt' => $article->getUpdateAt()->format('Y-m-d H:i:s'),
        ]);
    }

     #[Route('/api/articles', name: 'api_articles_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        // Décoder les données JSON reçues
        $data = json_decode($request->getContent(), true);
    
        // Vérifier que les champs requis sont présents
        if (
            empty($data['libelle']) ||
            empty($data['qte_stock']) ||
            empty($data['prix'])
        ) {
            return $this->json(['error' => 'Les champs libelle, qte_stock et prix sont requis'], 400);
        }
    
        // Créer une nouvelle instance de l'article
        $article = new Article();
        $reference = sprintf('%04d', $article->getId());
        $article->setReference($reference);
        $article->setLibelle($data['libelle']);
        $article->setQteStock($data['qte_stock']);
        $article->setPrix($data['prix']);
        $article->setEtat(EtatArticle::disponible); 
    
        // Valider les données de l'article
        $errors = $this->validator->validate($article);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }
    
        // Enregistrer l'article dans la base de données
        $this->articleRepository->save($article);
       
        // Mettre à jour la référence dans la base de données
        $this->articleRepository->save($article);
    
        // Retourner une réponse JSON avec succès
        return $this->json([
            'message' => 'Article créé avec succès',
            'id' => $article->getId(),
            'reference' => $reference
        ], 201);
    }
    
    // Mettre à jour un article
    #[Route('/api/articles/{id}', name: 'api_articles_edit', methods: ['PATCH'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $article = $this->articleRepository->findArticleById($id);

        if (!$article) {
            return $this->json(['error' => 'Article not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['reference'])) {
            $article->setReference($data['reference']);
        }
        if (isset($data['libelle'])) {
            $article->setLibelle($data['libelle']);
        }
        if (isset($data['qte_stock'])) {
            $article->setQteStock($data['qte_stock']);
        }
        if (isset($data['prix'])) {
            $article->setPrix($data['prix']);
        }
        if (isset($data['etat'])) {
            $article->setEtat(EtatArticle::from($data['etat']));
        }

        // Validation des données
        $errors = $this->validator->validate($article);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        // Sauvegarder les modifications
        $article->setUpdateAt(new \DateTimeImmutable()); // Mettre à jour la date de modification
        $this->articleRepository->save($article);

        return $this->json([
            'message' => 'Article updated successfully',
            'id' => $article->getId()
        ]);
    }
}
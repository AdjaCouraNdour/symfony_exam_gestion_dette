<?php

namespace App\Controller;

use App\Entity\Details;
use App\Repository\DetailsRepository;
use App\Repository\ArticleRepository;
use App\Repository\DetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DetailsController extends AbstractController
{
    private DetailsRepository $detailsRepository;
    private ArticleRepository $articleRepository;
    private DetteRepository $detteRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        DetailsRepository $detailsRepository, 
        ArticleRepository $articleRepository, 
        DetteRepository $detteRepository, 
        EntityManagerInterface $entityManager
    ) {
        $this->detailsRepository = $detailsRepository;
        $this->articleRepository = $articleRepository;
        $this->detteRepository = $detteRepository;
        $this->entityManager = $entityManager;
    }

    
    #[Route('api/details', name: 'api_details_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $details = $this->detailsRepository->findAll();

        $result = array_map(function (Details $detail) {
            return [
                'id' => $detail->getId(),
                'qteDette' => $detail->getQteDette(),
                'article' => [
                    'id' => $detail->getArticle()->getId(),
                    'libelle' => $detail->getArticle()->getLibelle(),  // Vous pouvez ajuster en fonction des attributs de votre entité Article
                ],
                'dette' => [
                    'id' => $detail->getDette()->getId(),
                    'montant' => $detail->getDette()->getMontant(),  // Assurez-vous que votre entité Dette a ces propriétés
                ],
            ];
        }, $details);

        return $this->json($result);
    }


    #[Route('api/details/{id}', name: 'api_details_list', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $detail = $this->detailsRepository->find($id);
        if (!$detail) {
            return $this->json(['error' => 'Détail non trouvé'], 404);
        }

        return $this->json([
            'id' => $detail->getId(),
            'qteDette' => $detail->getQteDette(),
            'article' => [
                'id' => $detail->getArticle()->getId(),
                'libelle' => $detail->getArticle()->getLibelle(),
            ],
            'dette' => [
                'id' => $detail->getDette()->getId(),
                'montant' => $detail->getDette()->getMontant(),
            ],
        ]);
    }

   
    #[Route('api/details', name: 'api_details_create', methods: ['GET'])]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['qteDette'], $data['articleId'], $data['detteId'])) {
            return $this->json(['error' => 'Données manquantes'], 400);
        }

        $article = $this->articleRepository->find($data['articleId']);
        $dette = $this->detteRepository->find($data['detteId']);

        if (!$article) {
            return $this->json(['error' => 'Article non trouvé'], 404);
        }

        if (!$dette) {
            return $this->json(['error' => 'Dette non trouvée'], 404);
        }

        $detail = new Details();
        $detail->setQteDette($data['qteDette']);
        $detail->setArticle($article);
        $detail->setDette($dette);

        // Validation des données
        $errors = $validator->validate($detail);
        if (count($errors) > 0) {
            return $this->json(['error' => 'Données invalides'], 400);
        }

        // Persister l'objet
        $this->entityManager->persist($detail);
        $this->entityManager->flush();

        return $this->json(['message' => 'Détail créé avec succès'], 201);
    }

    #[Route('api/details/{id}', name: 'api_details_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $detail = $this->detailsRepository->find($id);
        if (!$detail) {
            return $this->json(['error' => 'Détail non trouvé'], 404);
        }

        try {
            $this->entityManager->remove($detail);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la suppression du détail : ' . $e->getMessage()], 500);
        }

        return $this->json(['message' => 'Détail supprimé avec succès']);
    }
}

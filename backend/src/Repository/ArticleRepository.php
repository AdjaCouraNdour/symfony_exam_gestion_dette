<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }
    public function findArticleByLibelle(string $libelle): ?Article
    {
        return $this->findOneBy(['Libelle' => $libelle]);
    }

    public function findArticleById(int $id): ?Article
    {
        return $this->find($id);
    }

    public function save(Article $article, bool $flush = true): void
    {
        $entityManager = $this->getEntityManager(); // Récupère l'EntityManager
        $entityManager->persist($article);
        if ($flush) {
            $entityManager->flush();
        }
    }
}
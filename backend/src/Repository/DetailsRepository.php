<?php

namespace App\Repository;

use App\Entity\Details;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Details::class);
    }

    public function findDetailsById(int $id): ?Details
    {
        return $this->find($id);
    }

    public function save(Details $Details, bool $flush = true): void
    {
        $entityManager = $this->getEntityManager(); // Récupère l'EntityManager
        $entityManager->persist($Details);
        if ($flush) {
            $entityManager->flush();
        }
    }
}
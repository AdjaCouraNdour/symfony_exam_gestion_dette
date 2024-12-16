<?php

namespace App\Repository;

use App\Entity\Dette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dette::class);
    }

    public function findDetteById(int $id): ?Dette
    {
        return $this->find($id);
    }
    

    public function save(Dette $Dette, bool $flush = true): void
    {
        $entityManager = $this->getEntityManager(); // Récupère l'EntityManager
        $entityManager->persist($Dette);
        if ($flush) {
            $entityManager->flush();
        }
    }
}
<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function findUserByLogin(string $login): ?User
    {
        return $this->findOneBy(['login' => $login]);
    }

    public function findUserByprenom(string $prenom): ?User
    {
        return $this->findOneBy(['prenom' => $prenom]);
    }

    public function findUserById(int $id): ?User
    {
        return $this->find($id);
    }

    public function save(User $User, bool $flush = true): void
    {
        $entityManager = $this->getEntityManager(); // Récupère l'EntityManager
        $entityManager->persist($User);
        if ($flush) {
            $entityManager->flush();
        }
    }
}
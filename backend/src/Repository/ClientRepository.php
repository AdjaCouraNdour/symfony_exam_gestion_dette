<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }
    public function findClientByPhone(string $phone): ?Client
    {
        return $this->findOneBy(['telephone' => $phone]);
    }

    public function findClientBySurname(string $surname): ?Client
    {
        return $this->findOneBy(['surname' => $surname]);
    }

    public function findClientById(int $id): ?Client
    {
        return $this->find($id);
    }

    public function save(Client $client, bool $flush = true): void
    {
        $entityManager = $this->getEntityManager(); // Récupère l'EntityManager
        $entityManager->persist($client);
        if ($flush) {
            $entityManager->flush();
        }
    }
}
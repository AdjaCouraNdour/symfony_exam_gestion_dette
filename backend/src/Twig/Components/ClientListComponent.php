<?php
namespace App\Twig\Components;

use App\Repository\ClientRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ClientListComponent
{
    use DefaultActionTrait;

    private ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    // Méthode pour récupérer tous les clients ou selon un filtre
    public function getClients(?bool $hasAccount = null): array
    {
        if ($hasAccount === null) {
            return $this->clientRepository->findAll();
        }

        return $this->clientRepository->findBy(['hasAccount' => $hasAccount]);
    }
}

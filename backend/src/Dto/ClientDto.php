<?php

// src/Dto/ClientDto.php
namespace App\Dto;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeImmutable;


class ClientDto
{
    /**
     * @Assert\NotBlank(message="Le prénom ne doit pas être vide.")
     * @Assert\Length(
     *      min = 2,
     *      max = 100,
     *      minMessage = "Le prénom doit contenir au moins {{ limit }} caractères.",
     *      maxMessage = "Le prénom ne peut pas dépasser {{ limit }} caractères."
     * )
     */
    private ?string $surname = null;

    /**
     * @Assert\NotBlank(message="Le téléphone ne doit pas être vide.")
     * @Assert\Regex(
     *     pattern="/^\+?\d{9,15}$/",
     *     message="Le numéro de téléphone doit être valide."
     * )
     */
    private ?string $telephone = null;

    /**
     * @Assert\NotBlank(message="L'adresse ne doit pas être vide.")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "L'adresse ne peut pas dépasser {{ limit }} caractères."
     * )
     */
    private ?string $adresse = null;

    /**
     * Par défaut, un client n'est pas bloqué.
     */
    private bool $isBlocked = false;

    /**
     * Date de création, initialisée lors de la création du DTO.
     */
    private \DateTimeImmutable $createAt;

    /**
     * Date de mise à jour, initialisée lors de la création du DTO.
     */
    private \DateTimeImmutable $updateAt;

    /**
     * Utilisateur associé, optionnel.
     */
    private ?User $userr = null;

    public function __construct()
    {
        $this->createAt = new \DateTimeImmutable();
        $this->updateAt = new \DateTimeImmutable();
    }

    // Getters et setters

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function isBlocked(): bool
    {
        return $this->isBlocked;
    }

    public function setBlocked(bool $isBlocked): void
    {
        $this->isBlocked = $isBlocked;
    }

    public function getCreateAt(): \DateTimeImmutable
    {
        return $this->createAt;
    }

    public function getUpdateAt(): \DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): void
    {
        $this->updateAt = $updateAt;
    }

    public function getUserr(): ?User
    {
        return $this->userr;
    }

    public function setUserr(?User $userr): void
    {
        $this->userr = $userr;
    }
}

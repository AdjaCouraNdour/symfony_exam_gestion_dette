<?php

namespace App\Dto;

class ClientSearchDto
{
    private ?string $telephone = null;
    private ?string $surname = null;

    private ?string $adresse = null;

    private ?bool $compte = null;

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;
        return $this;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getCompte(): ?bool
    {
        return $this->compte;
    }

    public function setCompte(?bool $compte): self
    {
        $this->compte = $compte;
        return $this;
    }
}
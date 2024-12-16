<?php

namespace App\Entity;

use App\Enums\EtatArticle;
use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('libelle', message:'le libelle doit etre unique')]

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $reference = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $libelle = null;
    
    #[ORM\Column(type: "float", precision: 15, scale: 2)]
    private ?float $qte_stock = null; 
    
    #[ORM\Column(type: "float", precision: 10, scale: 2)]
    private ?float $prix = null; 

    #[ORM\Column(type: "string", enumType: EtatArticle::class)]
    private ?EtatArticle $etat = null;

    #[ORM\Column(type: "datetime_immutable")]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(type: "datetime_immutable")]
    private ?\DateTimeImmutable $updateAt = null;

    public function __construct()
    {
        $this->createAt = new \DateTimeImmutable(); 
        $this->updateAt = new \DateTimeImmutable(); 
    } 

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getReference(): ?string
    {
        return $this->reference;
    }
    
    public function setReference(string $reference): self
    {
        $this->reference = $reference;    
        return $this;
    }
    
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }
    
    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;    
        return $this;
    }
    
    public function getQteStock(): ?float
    {
        return $this->qte_stock;
    }
    
    public function setQteStock(float $qte_stock): self
    {
        $this->qte_stock = $qte_stock;
        return $this;
    }
    
    public function getPrix(): ?float
    {
        return $this->prix;
    }
    
    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getEtat(): ?EtatArticle 
    {
        if ($this->qte_stock!=0 ) {
            $this->etat = EtatArticle::disponible;
        }
        return $this->etat;
    }
    
    public function setEtat(EtatArticle $etat): self 
    {
        $this->etat = $etat;
        return $this;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): self
    {
        $this->updateAt = $updateAt;
        return $this;
    }
}

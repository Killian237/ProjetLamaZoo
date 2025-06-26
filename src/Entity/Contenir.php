<?php

namespace App\Entity;

use App\Repository\ContenirRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContenirRepository::class)]
class Contenir
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'contenirs')]
    private ?Panier $panier = null;

    #[ORM\ManyToOne(inversedBy: 'contenirs')]
    private ?Ateliers $ateliers = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): static
    {
        $this->panier = $panier;

        return $this;
    }

    public function getAteliers(): ?Ateliers
    {
        return $this->ateliers;
    }

    public function setAteliers(?Ateliers $ateliers): static
    {
        $this->ateliers = $ateliers;

        return $this;
    }
}

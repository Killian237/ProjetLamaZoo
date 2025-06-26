<?php

namespace App\Entity;

use App\Repository\MettreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MettreRepository::class)]
class Mettre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'mettres')]
    private ?Animaux $animaux = null;

    #[ORM\ManyToOne(inversedBy: 'mettres')]
    private ?Panier $panier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnimaux(): ?Animaux
    {
        return $this->animaux;
    }

    public function setAnimaux(?Animaux $animaux): static
    {
        $this->animaux = $animaux;

        return $this;
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
}

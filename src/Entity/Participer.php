<?php

namespace App\Entity;

use App\Repository\ParticiperRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticiperRepository::class)]
class Participer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $HeureDebut = null;

    #[ORM\Column]
    private ?\DateTime $HeureFin = null;

    #[ORM\ManyToOne(inversedBy: 'participers')]
    private ?Ateliers $atelier = null;

    #[ORM\ManyToOne(inversedBy: 'participers')]
    private ?Personnel $personnel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeureDebut(): ?\DateTime
    {
        return $this->HeureDebut;
    }

    public function setHeureDebut(\DateTime $HeureDebut): static
    {
        $this->HeureDebut = $HeureDebut;

        return $this;
    }

    public function getHeureFin(): ?\DateTime
    {
        return $this->HeureFin;
    }

    public function setHeureFin(\DateTime $HeureFin): static
    {
        $this->HeureFin = $HeureFin;

        return $this;
    }

    public function getAtelier(): ?Ateliers
    {
        return $this->atelier;
    }

    public function setAtelier(?Ateliers $atelier): static
    {
        $this->atelier = $atelier;

        return $this;
    }

    public function getPersonnel(): ?Personnel
    {
        return $this->personnel;
    }

    public function setPersonnel(?Personnel $personnel): static
    {
        $this->personnel = $personnel;

        return $this;
    }
}

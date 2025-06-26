<?php

namespace App\Entity;

use App\Repository\ParrainerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParrainerRepository::class)]
class Parrainer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateParrainage = null;

    #[ORM\Column]
    private ?int $montant = null;

    #[ORM\ManyToOne(inversedBy: 'parrainers')]
    private ?Personnel $personnel = null;

    #[ORM\ManyToOne(inversedBy: 'parrainers')]
    private ?Animaux $animaux = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateParrainage(): ?\DateTime
    {
        return $this->dateParrainage;
    }

    public function setDateParrainage(\DateTime $dateParrainage): static
    {
        $this->dateParrainage = $dateParrainage;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): static
    {
        $this->montant = $montant;

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

    public function getAnimaux(): ?Animaux
    {
        return $this->animaux;
    }

    public function setAnimaux(?Animaux $animaux): static
    {
        $this->animaux = $animaux;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\EstHabiliteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstHabiliteRepository::class)]
class EstHabilite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $dateDebut = null;

    #[ORM\Column]
    private ?\DateTime $DateFin = null;

    #[ORM\ManyToOne(inversedBy: 'estHabilites')]
    private ?Personnel $personnel = null;

    #[ORM\ManyToOne(inversedBy: 'estHabilites')]
    private ?RoleApplicatif $roleApplicatif = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTime $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->DateFin;
    }

    public function setDateFin(\DateTime $DateFin): static
    {
        $this->DateFin = $DateFin;

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

    public function getRoleApplicatif(): ?RoleApplicatif
    {
        return $this->roleApplicatif;
    }

    public function setRoleApplicatif(?RoleApplicatif $roleApplicatif): static
    {
        $this->roleApplicatif = $roleApplicatif;

        return $this;
    }
}

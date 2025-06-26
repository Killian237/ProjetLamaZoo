<?php

namespace App\Entity;

use App\Repository\AnimauxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimauxRepository::class)]
class Animaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descrption = null;

    #[ORM\Column(nullable: true)]
    private ?int $parrainage = null;

    /**
     * @var Collection<int, Parrainer>
     */
    #[ORM\OneToMany(targetEntity: Parrainer::class, mappedBy: 'animaux')]
    private Collection $parrainers;

    /**
     * @var Collection<int, Mettre>
     */
    #[ORM\OneToMany(targetEntity: Mettre::class, mappedBy: 'animaux')]
    private Collection $mettres;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $image = null;

    public function __construct()
    {
        $this->parrainers = new ArrayCollection();
        $this->mettres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescrption(): ?string
    {
        return $this->descrption;
    }

    public function setDescrption(string $descrption): static
    {
        $this->descrption = $descrption;

        return $this;
    }

    public function getParrainage(): ?int
    {
        return $this->parrainage;
    }

    public function setParrainage(?int $parrainage): static
    {
        $this->parrainage = $parrainage;

        return $this;
    }

    /**
     * @return Collection<int, Parrainer>
     */
    public function getParrainers(): Collection
    {
        return $this->parrainers;
    }

    public function addParrainer(Parrainer $parrainer): static
    {
        if (!$this->parrainers->contains($parrainer)) {
            $this->parrainers->add($parrainer);
            $parrainer->setAnimaux($this);
        }

        return $this;
    }

    public function removeParrainer(Parrainer $parrainer): static
    {
        if ($this->parrainers->removeElement($parrainer)) {
            // set the owning side to null (unless already changed)
            if ($parrainer->getAnimaux() === $this) {
                $parrainer->setAnimaux(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Mettre>
     */
    public function getMettres(): Collection
    {
        return $this->mettres;
    }

    public function addMettre(Mettre $mettre): static
    {
        if (!$this->mettres->contains($mettre)) {
            $this->mettres->add($mettre);
            $mettre->setAnimaux($this);
        }

        return $this;
    }

    public function removeMettre(Mettre $mettre): static
    {
        if ($this->mettres->removeElement($mettre)) {
            // set the owning side to null (unless already changed)
            if ($mettre->getAnimaux() === $this) {
                $mettre->setAnimaux(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
}

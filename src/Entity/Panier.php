<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $dateCreation = null;

    #[ORM\Column]
    private ?bool $regler = False;

    #[ORM\ManyToOne(inversedBy: 'paniers')]
    private ?Personnel $personnel = null;

    /**
     * @var Collection<int, Mettre>
     */
    #[ORM\OneToMany(targetEntity: Mettre::class, mappedBy: 'panier')]
    private Collection $mettres;

    /**
     * @var Collection<int, Contenir>
     */
    #[ORM\OneToMany(targetEntity: Contenir::class, mappedBy: 'panier')]
    private Collection $contenirs;

    public function __construct()
    {
        $this->mettres = new ArrayCollection();
        $this->contenirs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTime $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function isRegler(): ?bool
    {
        return $this->regler;
    }

    public function setRegler(bool $regler): static
    {
        $this->regler = $regler;

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
            $mettre->setPanier($this);
        }

        return $this;
    }

    public function removeMettre(Mettre $mettre): static
    {
        if ($this->mettres->removeElement($mettre)) {
            // set the owning side to null (unless already changed)
            if ($mettre->getPanier() === $this) {
                $mettre->setPanier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contenir>
     */
    public function getContenirs(): Collection
    {
        return $this->contenirs;
    }

    public function addContenir(Contenir $contenir): static
    {
        if (!$this->contenirs->contains($contenir)) {
            $this->contenirs->add($contenir);
            $contenir->setPanier($this);
        }

        return $this;
    }

    public function removeContenir(Contenir $contenir): static
    {
        if ($this->contenirs->removeElement($contenir)) {
            // set the owning side to null (unless already changed)
            if ($contenir->getPanier() === $this) {
                $contenir->setPanier(null);
            }
        }

        return $this;
    }
}

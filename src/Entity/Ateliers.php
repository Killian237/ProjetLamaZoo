<?php

namespace App\Entity;

use App\Repository\AteliersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AteliersRepository::class)]
class Ateliers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTime $HeureDebut = null;

    #[ORM\Column]
    private ?\DateTime $HeureFin = null;

    #[ORM\Column]
    private ?int $prix = null;

    /**
     * @var Collection<int, Contenir>
     */
    #[ORM\OneToMany(targetEntity: Contenir::class, mappedBy: 'ateliers')]
    private Collection $contenirs;

    /**
     * @var Collection<int, Participer>
     */
    #[ORM\OneToMany(targetEntity: Participer::class, mappedBy: 'atelier')]
    private Collection $participers;

    public function __construct()
    {
        $this->contenirs = new ArrayCollection();
        $this->participers = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
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

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

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
            $contenir->setAteliers($this);
        }

        return $this;
    }

    public function removeContenir(Contenir $contenir): static
    {
        if ($this->contenirs->removeElement($contenir)) {
            // set the owning side to null (unless already changed)
            if ($contenir->getAteliers() === $this) {
                $contenir->setAteliers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Participer>
     */
    public function getParticipers(): Collection
    {
        return $this->participers;
    }

    public function addParticiper(Participer $participer): static
    {
        if (!$this->participers->contains($participer)) {
            $this->participers->add($participer);
            $participer->setAtelier($this);
        }

        return $this;
    }

    public function removeParticiper(Participer $participer): static
    {
        if ($this->participers->removeElement($participer)) {
            // set the owning side to null (unless already changed)
            if ($participer->getAtelier() === $this) {
                $participer->setAtelier(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\RoleApplicatifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleApplicatifRepository::class)]
class RoleApplicatif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * @var Collection<int, Application>
     */
    #[ORM\OneToMany(targetEntity: Application::class, mappedBy: 'Possede')]
    private Collection $applications;

    /**
     * @var Collection<int, EstHabilite>
     */
    #[ORM\OneToMany(targetEntity: EstHabilite::class, mappedBy: 'roleApplicatif')]
    private Collection $estHabilites;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->estHabilites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Application>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): static
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
            $application->setPossede($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getPossede() === $this) {
                $application->setPossede(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EstHabilite>
     */
    public function getEstHabilites(): Collection
    {
        return $this->estHabilites;
    }

    public function addEstHabilite(EstHabilite $estHabilite): static
    {
        if (!$this->estHabilites->contains($estHabilite)) {
            $this->estHabilites->add($estHabilite);
            $estHabilite->setRoleApplicatif($this);
        }

        return $this;
    }

    public function removeEstHabilite(EstHabilite $estHabilite): static
    {
        if ($this->estHabilites->removeElement($estHabilite)) {
            // set the owning side to null (unless already changed)
            if ($estHabilite->getRoleApplicatif() === $this) {
                $estHabilite->setRoleApplicatif(null);
            }
        }

        return $this;
    }
}

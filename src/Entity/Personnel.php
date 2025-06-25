<?php

namespace App\Entity;

use App\Repository\PersonnelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: PersonnelRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Personnel implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateNaissance = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $tel = null;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\OneToMany(targetEntity: Service::class, mappedBy: 'personnel')]
    private Collection $services;

    /**
     * @var Collection<int, HistoHabilitation>
     */
    #[ORM\OneToMany(targetEntity: HistoHabilitation::class, mappedBy: 'personnel')]
    private Collection $histoHabilitations;

    /**
     * @var Collection<int, EstHabilite>
     */
    #[ORM\OneToMany(targetEntity: EstHabilite::class, mappedBy: 'personnel')]
    private Collection $estHabilites;

    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->histoHabilitations = new ArrayCollection();
        $this->estHabilites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
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
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTime
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTime $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setPersonnel($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getPersonnel() === $this) {
                $service->setPersonnel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HistoHabilitation>
     */
    public function getHistoHabilitations(): Collection
    {
        return $this->histoHabilitations;
    }

    public function addHistoHabilitation(HistoHabilitation $histoHabilitation): static
    {
        if (!$this->histoHabilitations->contains($histoHabilitation)) {
            $this->histoHabilitations->add($histoHabilitation);
            $histoHabilitation->setPersonnel($this);
        }

        return $this;
    }

    public function removeHistoHabilitation(HistoHabilitation $histoHabilitation): static
    {
        if ($this->histoHabilitations->removeElement($histoHabilitation)) {
            // set the owning side to null (unless already changed)
            if ($histoHabilitation->getPersonnel() === $this) {
                $histoHabilitation->setPersonnel(null);
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
            $estHabilite->setPersonnel($this);
        }

        return $this;
    }

    public function removeEstHabilite(EstHabilite $estHabilite): static
    {
        if ($this->estHabilites->removeElement($estHabilite)) {
            // set the owning side to null (unless already changed)
            if ($estHabilite->getPersonnel() === $this) {
                $estHabilite->setPersonnel(null);
            }
        }

        return $this;
    }
}

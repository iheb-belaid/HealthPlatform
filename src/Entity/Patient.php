<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient extends User
{
    public function __construct()
    {
        parent::__construct();
        $this->setRoles(['ROLE_PATIENT']);
        $this->chirurgies = new ArrayCollection();
        $this->suivisMedicaux = new ArrayCollection();
    }

    #[ORM\Column(length: 255)]
    private ?string $prename = null;

    #[ORM\Column]
    private ?int $num_phone = null;

    #[ORM\Column(length: 255)]
    private ?string $sexe = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_naissance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    /**
     * @var Collection<int, Chirurgie>
     */
    #[ORM\OneToMany(targetEntity: Chirurgie::class, mappedBy: 'patient')]
    private Collection $chirurgies;

    /**
     * @var Collection<int, SuiviMedical>
     */
    #[ORM\OneToMany(targetEntity: SuiviMedical::class, mappedBy: 'patient')]
    private Collection $suivisMedicaux;

    // Getters et Setters
    public function getPrename(): ?string
    {
        return $this->prename;
    }

    public function setPrename(string $prename): static
    {
        $this->prename = $prename;
        return $this;
    }

    public function getNumPhone(): ?int
    {
        return $this->num_phone;
    }

    public function setNumPhone(int $num_phone): static
    {
        $this->num_phone = $num_phone;
        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;
        return $this;
    }

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->date_de_naissance;
    }

    public function setDateDeNaissance(\DateTimeInterface $date_de_naissance): static
    {
        $this->date_de_naissance = $date_de_naissance;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * @return Collection<int, Chirurgie>
     */
    public function getChirurgies(): Collection
    {
        return $this->chirurgies;
    }

    public function addChirurgie(Chirurgie $chirurgie): static
    {
        if (!$this->chirurgies->contains($chirurgie)) {
            $this->chirurgies->add($chirurgie);
            $chirurgie->setPatient($this);
        }
        return $this;
    }

    public function removeChirurgie(Chirurgie $chirurgie): static
    {
        if ($this->chirurgies->removeElement($chirurgie)) {
            if ($chirurgie->getPatient() === $this) {
                $chirurgie->setPatient(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, SuiviMedical>
     */
    public function getSuivisMedicaux(): Collection
    {
        return $this->suivisMedicaux;
    }

    public function addSuiviMedical(SuiviMedical $suiviMedical): static
    {
        if (!$this->suivisMedicaux->contains($suiviMedical)) {
            $this->suivisMedicaux->add($suiviMedical);
            $suiviMedical->setPatient($this);
        }
        return $this;
    }

    public function removeSuiviMedical(SuiviMedical $suiviMedical): static
    {
        if ($this->suivisMedicaux->removeElement($suiviMedical)) {
            if ($suiviMedical->getPatient() === $this) {
                $suiviMedical->setPatient(null);
            }
        }
        return $this;
    }
}
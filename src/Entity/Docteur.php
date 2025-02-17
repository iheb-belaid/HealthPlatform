<?php

namespace App\Entity;

use App\Repository\DocteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocteurRepository::class)]
class Docteur extends User
{    
    public function __construct()
    {
        parent::__construct();
        $this->setRoles(['ROLE_DOCTEUR']);
        $this->suivisMedicaux = new ArrayCollection();
        $this->consultations = new ArrayCollection();
    }

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?int $phoneNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $gender = null;

    #[ORM\Column(length: 255)]
    private ?string $specialty = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    /**
     * @var Collection<int, SuiviMedical>
     */
    #[ORM\OneToMany(targetEntity: SuiviMedical::class, mappedBy: 'docteur')]
    private Collection $suivisMedicaux;

    /**
     * @var Collection<int, Consultation>
     */
    #[ORM\OneToMany(targetEntity: Consultation::class, mappedBy: 'docteur')]
    private Collection $consultations;

    // Getters et Setters
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;
        return $this;
    }

    public function getSpecialty(): ?string
    {
        return $this->specialty;
    }

    public function setSpecialty(string $specialty): static
    {
        $this->specialty = $specialty;
        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;
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
            $suiviMedical->setDocteur($this);
        }
        return $this;
    }

    public function removeSuiviMedical(SuiviMedical $suiviMedical): static
    {
        if ($this->suivisMedicaux->removeElement($suiviMedical)) {
            if ($suiviMedical->getDocteur() === $this) {
                $suiviMedical->setDocteur(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Consultation>
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): static
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations->add($consultation);
            $consultation->setDocteur($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): static
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getDocteur() === $this) {
                $consultation->setDocteur(null);
            }
        }

        return $this;
    }
}
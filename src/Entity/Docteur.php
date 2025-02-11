<?php

namespace App\Entity;

use App\Repository\DocteurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocteurRepository::class)]
class Docteur extends User
{    
    public function __construct()
    {
        parent::__construct();
        $this->setRoles(['ROLE_DOCTEUR']);
    }

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;  // Renommé en firstName (pour respecter la convention)

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;  // Renommé en lastName (pour respecter la convention)

    #[ORM\Column]
    private ?int $phoneNumber = null;  // Renommé en phoneNumber (pour plus de clarté)

    #[ORM\Column(length: 255)]
    private ?string $gender = null;  // Renommé en gender

    #[ORM\Column(length: 255)]
    private ?string $specialty = null;  // Renommé en specialty (respecter la convention)

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthDate = null;  // Renommé en birthDate

    #[ORM\Column(length: 255)]
    private ?string $city = null;  // Ajout d'une propriété 'city' pour la ville

    // Getter et setter pour 'firstName'
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    // Getter et setter pour 'lastName'
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    // Getter et setter pour 'phoneNumber'
    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    // Getter et setter pour 'gender'
    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;
        return $this;
    }

    // Getter et setter pour 'specialty'
    public function getSpecialty(): ?string
    {
        return $this->specialty;
    }

    public function setSpecialty(string $specialty): static
    {
        $this->specialty = $specialty;
        return $this;
    }

    // Getter et setter pour 'birthDate'
    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    // Getter et setter pour 'city'
    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;
        return $this;
    }
}

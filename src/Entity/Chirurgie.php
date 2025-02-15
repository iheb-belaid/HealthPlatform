<?php

namespace App\Entity;

use App\Repository\ChirurgieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ChirurgieRepository::class)]
class Chirurgie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de l'opération ne peut pas être vide.")]
    #[Assert\Regex(
        pattern: "/^\s*$/",
        match: false,
        message: "Le nom de l'opération ne peut pas être uniquement composé d'espaces."
    )]
    private ?string $nom_operation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "La date de la chirurgie ne peut pas être vide.")]
    private ?\DateTimeInterface $date_chirurgie = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de l'établissement ne peut pas être vide.")]
    #[Assert\Regex(
        pattern: "/^\s*$/",
        match: false,
        message: "Le nom de l'établissement ne peut pas être uniquement composé d'espaces."
    )]
    private ?string $nom_etablissement = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: "Les notes ne peuvent pas être vides.")]
    private ?string $notes = null;

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: 'chirurgies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $patient = null;

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomOperation(): ?string
    {
        return $this->nom_operation;
    }

    public function setNomOperation(string $nom_operation): static
    {
        $this->nom_operation = $nom_operation;
        return $this;
    }

    public function getDateChirurgie(): ?\DateTimeInterface
    {
        return $this->date_chirurgie;
    }

    public function setDateChirurgie(\DateTimeInterface $date_chirurgie): static
    {
        $this->date_chirurgie = $date_chirurgie;
        return $this;
    }

    public function getNomEtablissement(): ?string
    {
        return $this->nom_etablissement;
    }

    public function setNomEtablissement(string $nom_etablissement): static
    {
        $this->nom_etablissement = $nom_etablissement;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;
        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;
        return $this;
    }
}
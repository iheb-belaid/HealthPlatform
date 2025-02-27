<?php // src/Entity/Chirurgie.php
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ChirurgieRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ChirurgieRepository::class)]
#[Vich\Uploadable]
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

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
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

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du docteur ne peut pas être vide.")]
    #[Assert\Regex(
        pattern: "/^\s*$/",
        match: false,
        message: "Le nom du docteur ne peut pas être uniquement composé d'espaces."
    )]
    private ?string $nom_docteur = null;

    #[Vich\UploadableField(mapping: "chirurgie_files", fileNameProperty: "rapportChirurgieName")]
    private ?File $rapportChirurgieFile = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $rapportChirurgieName = null;

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

    public function getNomDocteur(): ?string
    {
        return $this->nom_docteur;
    }

    public function setNomDocteur(string $nom_docteur): static
    {
        $this->nom_docteur = $nom_docteur;
        return $this;
    }

    public function setRapportChirurgieFile(?File $rapportChirurgieFile = null): void
    {
        $this->rapportChirurgieFile = $rapportChirurgieFile;
    }

    public function getRapportChirurgieFile(): ?File
    {
        return $this->rapportChirurgieFile;
    }

    public function setRapportChirurgieName(?string $rapportChirurgieName): void
    {
        $this->rapportChirurgieName = $rapportChirurgieName;
    }

    public function getRapportChirurgieName(): ?string
    {
        return $this->rapportChirurgieName;
    }
}
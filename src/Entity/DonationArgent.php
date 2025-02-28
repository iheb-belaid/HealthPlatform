<?php

namespace App\Entity;

use App\Repository\DonationArgentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DonationArgentRepository::class)]
class DonationArgent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le montant est obligatoire.")]
    #[Assert\Positive(message: "Le montant doit être un nombre positif.")]
    private ?float $montant = null;



    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $date = null;
    
    public function __construct()
    {
        $this->date = new \DateTime(); // Assigne la date actuelle lors de la création
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

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;
        return $this;
    }

   

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }
    #[ORM\ManyToOne(targetEntity: Hospital::class, inversedBy: 'donationsArgent')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Vous devez sélectionner un hôpital.")]
    private ?Hospital $hospital = null;
    
    public function getHospital(): ?Hospital
    {
        return $this->hospital;
    }
    
    public function setHospital(?Hospital $hospital): static
    {
        $this->hospital = $hospital;
        return $this;
    }
    
}

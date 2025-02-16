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

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La méthode de paiement est obligatoire.")]
    #[Assert\Choice(
        choices: ["carte", "paypal", "virement"],
        message: "Choisissez une méthode de paiement valide : Carte bancaire, PayPal ou Virement bancaire."
    )]
    private ?string $methodePaiment = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date est obligatoire.")]
    #[Assert\GreaterThanOrEqual("today", message: "La date ne peut pas être dans le passé.")]
    private ?\DateTimeInterface $date = null;

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

    public function getMethodePaiment(): ?string
    {
        return $this->methodePaiment;
    }

    public function setMethodePaiment(string $methodePaiment): static
    {
        $this->methodePaiment = strtolower($methodePaiment); // Stocker en minuscule pour éviter les erreurs
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
}

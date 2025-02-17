<?php

namespace App\Entity;

use App\Repository\DonationSangRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DonationSangRepository::class)]
class DonationSang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type de sang est obligatoire.")]
    #[Assert\Choice(
        choices: ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"],
        message: "Veuillez sélectionner un groupe sanguin valide."
    )]
    private ?string $TypeSang = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $Date_Donation = null;
    
    public function __construct()
    {
        $this->Date_Donation = new \DateTime(); // Assigne la date actuelle lors de la création
    }
    

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'email n'est pas valide.")]
    private ?string $EmailUser = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le CIN est obligatoire.")]
    #[Assert\Length(min: 8, max: 8, exactMessage: "Le CIN doit contenir exactement 8 chiffres.")]
    #[Assert\Regex(pattern: "/^\d+$/", message: "Le CIN doit contenir uniquement des chiffres.")]
    private ?int $Cin = null;

    #[ORM\ManyToOne(inversedBy: 'donation')]
    #[Assert\NotNull(message: "Vous devez sélectionner un hôpital.")]
    private ?Hospital $hospital = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeSang(): ?string
    {
        return $this->TypeSang;
    }

    public function setTypeSang(string $TypeSang): static
    {
        $this->TypeSang = $TypeSang;

        return $this;
    }

    public function getDateDonation(): ?\DateTimeInterface
    {
        return $this->Date_Donation;
    }

    public function setDateDonation(\DateTimeInterface $Date_Donation): static
    {
        $this->Date_Donation = $Date_Donation;

        return $this;
    }

    public function getEmailUser(): ?string
    {
        return $this->EmailUser;
    }

    public function setEmailUser(string $EmailUser): static
    {
        $this->EmailUser = $EmailUser;

        return $this;
    }

    public function getCin(): ?int
    {
        return $this->Cin;
    }

    public function setCin(int $Cin): static
    {
        $this->Cin = $Cin;

        return $this;
    }

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

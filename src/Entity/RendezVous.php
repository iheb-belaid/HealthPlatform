<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank(message: "La date est obligatoire.")]
    #[Assert\Type("\DateTimeInterface", message: "La date doit être une date valide.")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'time')]
    #[Assert\NotBlank(message: "L'heure est obligatoire.")]
    #[Assert\Type("\DateTimeInterface", message: "L'heure doit être une heure valide.")]
    private ?\DateTimeInterface $heure = null;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank(message: "Le statut est obligatoire.")]
    #[Assert\Length(max: 50, maxMessage: "Le statut ne doit pas dépasser {{ limit }} caractères.")]
    private ?string $statut = null;


    



    public function getId(): ?int
    {
        return $this->id;
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

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}

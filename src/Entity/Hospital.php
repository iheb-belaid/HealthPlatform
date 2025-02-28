<?php

namespace App\Entity;

use App\Repository\HospitalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HospitalRepository::class)]
class Hospital
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $Adresse = null;

    /**
     * @var Collection<int, DonationSang>
     */
    #[ORM\OneToMany(targetEntity: DonationSang::class, mappedBy: 'hospital')]
    private Collection $donation;
 /**
 * @var Collection<int, DonationArgent>
 */
#[ORM\OneToMany(targetEntity: DonationArgent::class, mappedBy: 'hospital', cascade: ['persist', 'remove'])]
private Collection $donationsArgent;

    public function __construct()
    {
        $this->donation = new ArrayCollection();
        $this->donationsArgent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): static
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    /**
     * @return Collection<int, DonationSang>
     */
    public function getDonation(): Collection
    {
        return $this->donation;
    }

    public function addDonation(DonationSang $donation): static
    {
        if (!$this->donation->contains($donation)) {
            $this->donation->add($donation);
            $donation->setHospital($this);
        }

        return $this;
    }

    public function removeDonation(DonationSang $donation): static
    {
        if ($this->donation->removeElement($donation)) {
            // set the owning side to null (unless already changed)
            if ($donation->getHospital() === $this) {
                $donation->setHospital(null);
            }
        }

        return $this;
    }
      /**
 * @return Collection<int, DonationArgent>
 */
public function getDonationsArgent(): Collection
{
    return $this->donationsArgent;
}

public function addDonationsArgent(DonationArgent $donationsArgent): static
{
    if (!$this->donationsArgent->contains($donationsArgent)) {
        $this->donationsArgent->add($donationsArgent);
        $donationsArgent->setHospital($this);
    }

    return $this;
}
}

<?php

namespace App\Entity;

use App\Repository\ShippingAddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\FormTypeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShippingAddressRepository::class)]
class ShippingAddress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column]
    private ?int $building_nr = null;

    #[ORM\Column(nullable: true)]
    private ?int $locale_nr = null;

    #[ORM\Column]
    private ?int $postcode = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column]
    private ?int $phone_nr = null;

    #[ORM\OneToMany(mappedBy: 'address', targetEntity: Shipping::class)]
    private Collection $shippings;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    private ?User $user = null;


    public function __construct()
    {
        $this->shippings = new ArrayCollection();
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getBuildingNr(): ?int
    {
        return $this->building_nr;
    }

    public function setBuildingNr(int $building_nr): static
    {
        $this->building_nr = $building_nr;

        return $this;
    }

    public function getLocaleNr(): ?int
    {
        return $this->locale_nr;
    }

    public function setLocaleNr(?int $locale_nr): static
    {
        $this->locale_nr = $locale_nr;

        return $this;
    }

    public function getPostcode(): ?int
    {
        return $this->postcode;
    }

    public function setPostcode(int $postcode): static
    {
        $this->postcode = $postcode;

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

    public function getPhoneNr(): ?int
    {
        return $this->phone_nr;
    }

    public function setPhoneNr(int $phone_nr): static
    {
        $this->phone_nr = $phone_nr;

        return $this;
    }

    /**
     * @return Collection<int, Shipping>
     */
    public function getShippings(): Collection
    {
        return $this->shippings;
    }

    public function addShipping(Shipping $shipping): static
    {
        if (!$this->shippings->contains($shipping)) {
            $this->shippings->add($shipping);
            $shipping->setAddress($this);
        }

        return $this;
    }

    public function removeShipping(Shipping $shipping): static
    {
        if ($this->shippings->removeElement($shipping)) {
            // set the owning side to null (unless already changed)
            if ($shipping->getAddress() === $this) {
                $shipping->setAddress(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}

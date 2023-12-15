<?php

namespace App\Entity;

use App\Repository\ShippingStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShippingStatusRepository::class)]
class ShippingStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'status', targetEntity: Shipping::class)]
    private Collection $shippings;

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
            $shipping->setStatus($this);
        }

        return $this;
    }

    public function removeShipping(Shipping $shipping): static
    {
        if ($this->shippings->removeElement($shipping)) {
            // set the owning side to null (unless already changed)
            if ($shipping->getStatus() === $this) {
                $shipping->setStatus(null);
            }
        }

        return $this;
    }
}

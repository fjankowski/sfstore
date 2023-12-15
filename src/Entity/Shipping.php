<?php

namespace App\Entity;

use App\Repository\ShippingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShippingRepository::class)]
class Shipping
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'shippings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShippingMethod $method = null;

    #[ORM\ManyToOne(inversedBy: 'shippings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShippingStatus $status = null;

    #[ORM\ManyToOne(inversedBy: 'shippings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShippingAddress $address = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $shipped_date = null;

    #[ORM\Column(nullable: true)]
    private ?int $tracking = null;

    #[ORM\OneToMany(mappedBy: 'shipping', targetEntity: Order::class)]
    private Collection $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMethod(): ?ShippingMethod
    {
        return $this->method;
    }

    public function setMethod(?ShippingMethod $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function getStatus(): ?ShippingStatus
    {
        return $this->status;
    }

    public function setStatus(?ShippingStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAddress(): ?ShippingAddress
    {
        return $this->address;
    }

    public function setAddress(?ShippingAddress $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getShippedDate(): ?\DateTimeInterface
    {
        return $this->shipped_date;
    }

    public function setShippedDate(?\DateTimeInterface $shipped_date): static
    {
        $this->shipped_date = $shipped_date;

        return $this;
    }

    public function getTracking(): ?int
    {
        return $this->tracking;
    }

    public function setTracking(?int $tracking): static
    {
        $this->tracking = $tracking;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setShipping($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getShipping() === $this) {
                $order->setShipping(null);
            }
        }

        return $this;
    }
}

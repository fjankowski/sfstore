<?php

namespace App\Entity;

use App\Repository\OrderProductEntryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderProductEntryRepository::class)]
class OrderProductEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order_ref = null;

    #[ORM\Column]
    private ?int $count = null;

    #[ORM\ManyToOne(inversedBy: 'orderProductEntries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShopItem $item = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderRef(): ?Order
    {
        return $this->order_ref;
    }

    public function setOrderRef(?Order $order_ref): static
    {
        $this->order_ref = $order_ref;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): static
    {
        $this->count = $count;

        return $this;
    }

    public function getItem(): ?ShopItem
    {
        return $this->item;
    }

    public function setItem(?ShopItem $item): static
    {
        $this->item = $item;

        return $this;
    }
}

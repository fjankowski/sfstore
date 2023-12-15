<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaymentMethod $method = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaymentStatus $status = null;

    #[ORM\Column]
    private ?float $paid_amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMethod(): ?PaymentMethod
    {
        return $this->method;
    }

    public function setMethod(?PaymentMethod $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function getStatus(): ?PaymentStatus
    {
        return $this->status;
    }

    public function setStatus(?PaymentStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPaidAmount(): ?float
    {
        return $this->paid_amount;
    }

    public function setPaidAmount(float $paid_amount): static
    {
        $this->paid_amount = $paid_amount;

        return $this;
    }
}

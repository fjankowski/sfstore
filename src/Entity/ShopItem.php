<?php

namespace App\Entity;

use App\Repository\ShopItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints;

#[ORM\Entity(repositoryClass: ShopItemRepository::class)]
class ShopItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Constraints\NotBlank()]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Category", inversedBy:"item")]
    private ?Category $category;

    #[ORM\Column]
    private ?bool $is_hidden = null;

    #[ORM\Column]
    private ?bool $require_login = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\OneToMany(mappedBy: 'item', targetEntity: OrderProductEntry::class)]
    private Collection $orderProductEntries;

    public function __construct()
    {
        $this->orderProductEntries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function isIsHidden(): ?bool
    {
        return $this->is_hidden;
    }

    public function setIsHidden(bool $is_hidden): static
    {
        $this->is_hidden = $is_hidden;

        return $this;
    }

    public function isRequireLogin(): ?bool
    {
        return $this->require_login;
    }

    public function setRequireLogin(bool $require_login): static
    {
        $this->require_login = $require_login;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, OrderProductEntry>
     */
    public function getOrderProductEntries(): Collection
    {
        return $this->orderProductEntries;
    }

    public function addOrderProductEntry(OrderProductEntry $orderProductEntry): static
    {
        if (!$this->orderProductEntries->contains($orderProductEntry)) {
            $this->orderProductEntries->add($orderProductEntry);
            $orderProductEntry->setItem($this);
        }

        return $this;
    }

    public function removeOrderProductEntry(OrderProductEntry $orderProductEntry): static
    {
        if ($this->orderProductEntries->removeElement($orderProductEntry)) {
            // set the owning side to null (unless already changed)
            if ($orderProductEntry->getItem() === $this) {
                $orderProductEntry->setItem(null);
            }
        }

        return $this;
    }
}

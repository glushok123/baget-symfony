<?php

namespace App\Entity\Product;

use App\Entity\Embeddable\Image;
use App\Entity\Embeddable\Price;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embedded;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[Groups(['searchable'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $externalId = null;

    #[Groups(['searchable'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['searchable'])]
    #[ORM\Column(length: 255)]
    private ?string $article = null;

    #[ORM\Column]
    private ?bool $colorBlack = null;

    #[ORM\Column]
    private ?bool $colorMagenta = null;

    #[ORM\Column]
    private ?bool $colorYellow = null;

    #[ORM\Column]
    private ?bool $colorCyan = null;

    #[ORM\Column]
    private ?bool $colorWhite = null;

    #[ORM\Column]
    private ?bool $colorTransparent = null;

    #[ORM\Column]
    private ?bool $formatA0 = null;

    #[ORM\Column]
    private ?bool $formatA1 = null;

    #[ORM\Column]
    private ?bool $formatA2 = null;

    #[ORM\Column]
    private ?bool $formatA3 = null;

    #[ORM\Column]
    private ?bool $formatA4 = null;

    #[MaxDepth(1)]
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'children')]
    private Collection $parent;

    #[MaxDepth(1)]
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $children;

    #[Groups(['searchable'])]
    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?ProductCategory $category = null;

    #[Groups(['searchable'])]
    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?ProductType $type = null;

    #[MaxDepth(1)]
    #[Groups(['searchable'])]
    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?ProductBrand $brand = null;

    #[Groups(['searchable'])]
    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?ProductModel $model = null;

    #[ORM\Column]
    private ?bool $deleted = false;

    #[ORM\Column]
    private ?bool $active = true;

    #[Embedded(class: Image::class)]
    private Image $image;

    #[Groups(['searchable'])]
    #[Embedded(class: Price::class)]
    private Price $price;

    #[ORM\Column(nullable: true)]
    private ?int $count = null;

    #[ORM\Column(nullable: true)]
    private ?int $countTransit = null;

    #[ORM\Column(nullable: true)]
    private ?int $minCount = null;

    #[MaxDepth(1)]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[MaxDepth(1)]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $sortingNumber = null;

    public function __construct()
    {
        $this->parent = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->image = new Image();
        $this->price = new Price();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalId(): ?int
    {
        return $this->externalId;
    }

    public function setExternalId(int $externalId): static
    {
        $this->externalId = $externalId;

        return $this;
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

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(string $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function isColorBlack(): ?bool
    {
        return $this->colorBlack;
    }

    public function setColorBlack(bool $colorBlack): static
    {
        $this->colorBlack = $colorBlack;

        return $this;
    }

    public function isColorMagenta(): ?bool
    {
        return $this->colorMagenta;
    }

    public function setColorMagenta(bool $colorMagenta): static
    {
        $this->colorMagenta = $colorMagenta;

        return $this;
    }

    public function isColorYellow(): ?bool
    {
        return $this->colorYellow;
    }

    public function setColorYellow(bool $colorYellow): static
    {
        $this->colorYellow = $colorYellow;

        return $this;
    }

    public function isColorCyan(): ?bool
    {
        return $this->colorCyan;
    }

    public function setColorCyan(bool $colorCyan): static
    {
        $this->colorCyan = $colorCyan;

        return $this;
    }

    public function isColorWhite(): ?bool
    {
        return $this->colorWhite;
    }

    public function setColorWhite(bool $colorWhite): static
    {
        $this->colorWhite = $colorWhite;

        return $this;
    }

    public function isColorTransparent(): ?bool
    {
        return $this->colorTransparent;
    }

    public function setColorTransparent(bool $colorTransparent): static
    {
        $this->colorTransparent = $colorTransparent;

        return $this;
    }

    public function isFormatA0(): ?bool
    {
        return $this->formatA0;
    }

    public function setFormatA0(bool $formatA0): static
    {
        $this->formatA0 = $formatA0;

        return $this;
    }

    public function isFormatA1(): ?bool
    {
        return $this->formatA1;
    }

    public function setFormatA1(bool $formatA1): static
    {
        $this->formatA1 = $formatA1;

        return $this;
    }

    public function isFormatA2(): ?bool
    {
        return $this->formatA2;
    }

    public function setFormatA2(bool $formatA2): static
    {
        $this->formatA2 = $formatA2;

        return $this;
    }

    public function isFormatA3(): ?bool
    {
        return $this->formatA3;
    }

    public function setFormatA3(bool $formatA3): static
    {
        $this->formatA3 = $formatA3;

        return $this;
    }

    public function isFormatA4(): ?bool
    {
        return $this->formatA4;
    }

    public function setFormatA4(bool $formatA4): static
    {
        $this->formatA4 = $formatA4;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getParent(): Collection
    {
        return $this->parent;
    }

    public function addParent(self $parent): static
    {
        if (!$this->parent->contains($parent)) {
            $this->parent->add($parent);
        }

        return $this;
    }

    public function removeParent(self $parent): static
    {
        $this->parent->removeElement($parent);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->addParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->children->removeElement($child)) {
            $child->removeParent($this);
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): static
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function isSearchable(): ?bool
    {
        return $this->active && !$this->deleted;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getCategory(): ?ProductCategory
    {
        return $this->category;
    }

    public function setCategory(?ProductCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getBrand(): ?ProductBrand
    {
        return $this->brand;
    }

    public function setBrand(?ProductBrand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?ProductModel
    {
        return $this->model;
    }

    public function setModel(?ProductModel $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getType(): ?ProductType
    {
        return $this->type;
    }

    public function setType(?ProductType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): Image
    {
        return $this->image;
    }

    public function setImage(Image $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): static
    {
        $this->count = $count;

        return $this;
    }

    public function getCountTransit(): ?int
    {
        return $this->countTransit;
    }

    public function setCountTransit(?int $countTransit): static
    {
        $this->countTransit = $countTransit;

        return $this;
    }

    public function getMinCount(): ?int
    {
        return $this->minCount;
    }

    public function setMinCount(?int $minCount): static
    {
        $this->minCount = $minCount;

        return $this;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function setPrice(Price $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSortingNumber(): ?int
    {
        return $this->sortingNumber;
    }

    public function setSortingNumber(?int $sortingNumber): static
    {
        $this->sortingNumber = $sortingNumber;

        return $this;
    }
}

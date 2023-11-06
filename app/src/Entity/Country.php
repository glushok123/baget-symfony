<?php

namespace App\Entity;

use App\Entity\Organization\Organization;
use App\Entity\Organization\OrganizationAddress;
use App\Repository\CountryRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    private ?string $shortName = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    #[MaxDepth(1)]
    #[Ignore]
    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Organization::class)]
    private Collection $organizations;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: OrganizationAddress::class)]
    private Collection $organizationAddresses;

    public function __construct()
    {
        $this->organizations = new ArrayCollection();
        $this->organizationAddresses = new ArrayCollection();
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

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): static
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Organization>
     */
    public function getOrganizations(): Collection
    {
        return $this->organizations;
    }

    public function addOrganization(Organization $organization): static
    {
        if (!$this->organizations->contains($organization)) {
            $this->organizations->add($organization);
            $organization->setCountry($this);
        }

        return $this;
    }

    public function removeOrganization(Organization $organization): static
    {
        if ($this->organizations->removeElement($organization)) {
            // set the owning side to null (unless already changed)
            if ($organization->getCountry() === $this) {
                $organization->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrganizationAddress>
     */
    public function getOrganizationAddresses(): Collection
    {
        return $this->organizationAddresses;
    }

    public function addOrganizationAddress(OrganizationAddress $organizationAddress): static
    {
        if (!$this->organizationAddresses->contains($organizationAddress)) {
            $this->organizationAddresses->add($organizationAddress);
            $organizationAddress->setCountry($this);
        }

        return $this;
    }

    public function removeOrganizationAddress(OrganizationAddress $organizationAddress): static
    {
        if ($this->organizationAddresses->removeElement($organizationAddress)) {
            // set the owning side to null (unless already changed)
            if ($organizationAddress->getCountry() === $this) {
                $organizationAddress->setCountry(null);
            }
        }

        return $this;
    }
}

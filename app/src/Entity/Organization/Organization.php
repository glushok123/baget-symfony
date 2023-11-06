<?php

namespace App\Entity\Organization;

use App\Entity\Country;
use App\Entity\User\User;
use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
class Organization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $active = false;

    #[ORM\Column(nullable: true)]
    private ?bool $approved = null;

    #[ORM\Column(length: 12, unique: true)]
    private ?string $inn = null;

    #[Ignore]
    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'organizations')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $owner = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: '$organizationsEmployee')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $employee = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $legalAddress = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "phone_number", length: 255, unique: true)]
    private ?PhoneNumber $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $site = null;

    #[ORM\Column]
    private ?bool $deleted = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[MaxDepth(1)]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?OrganizationType $typePublic = null;

    #[MaxDepth(1)]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?OrganizationType $typePrivate = null;

    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'organizations')]
    #[ORM\JoinColumn(nullable: true)]
    private ?OrganizationGroup $group = null;

    #[MaxDepth(1)]
    #[ORM\OneToMany(mappedBy: 'organization', targetEntity: OrganizationMessenger::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $messengers;

    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'organizations')]
    private ?Country $country = null;

    #[MaxDepth(1)]
    #[ORM\OneToMany(mappedBy: 'organization', targetEntity: OrganizationAddress::class)]
    private Collection $organizationAddresses;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shortName = null;

    public function __construct()
    {
        $this->messengers = new ArrayCollection();
        $this->organizationAddresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): static
    {
        $this->approved = $approved;

        return $this;
    }

    public function getInn(): ?string
    {
        return $this->inn;
    }

    public function setInn(string $inn): static
    {
        $this->inn = $inn;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    public function setEmployee(?User $employee): static
    {
        $this->employee = $employee;

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

    public function getLegalAddress(): ?string
    {
        return $this->legalAddress;
    }

    public function setLegalAddress(string $legalAddress): static
    {
        $this->legalAddress = $legalAddress;

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

    public function getPhone(): ?PhoneNumber
    {
        return $this->phone;
    }

    public function setPhone(?PhoneNumber $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(?string $site): static
    {
        $this->site = $site;

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

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTypePublic(): ?OrganizationType
    {
        return $this->typePublic;
    }

    public function setTypePublic(?OrganizationType $typePublic): static
    {
        $this->typePublic = $typePublic;

        return $this;
    }

    public function getTypePrivate(): ?OrganizationType
    {
        return $this->typePrivate;
    }

    public function setTypePrivate(?OrganizationType $typePrivate): static
    {
        $this->typePrivate = $typePrivate;

        return $this;
    }

    public function getGroup(): ?OrganizationGroup
    {
        return $this->group;
    }

    public function setGroup(?OrganizationGroup $group): static
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return Collection<int, OrganizationMessenger>
     */
    public function getOrganizationMessengers(): Collection
    {
        return $this->messengers;
    }

    public function addOrganizationMessenger(OrganizationMessenger $organizationMessenger): static
    {
        if (!$this->messengers->contains($organizationMessenger)) {
            $this->messengers->add($organizationMessenger);
            $organizationMessenger->setOrganization($this);
        }

        return $this;
    }

    public function removeOrganizationMessenger(OrganizationMessenger $organizationMessenger): static
    {
        if ($this->messengers->removeElement($organizationMessenger)) {
            // set the owning side to null (unless already changed)
            if ($organizationMessenger->getOrganization() === $this) {
                $organizationMessenger->setOrganization(null);
            }
        }

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

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
            $organizationAddress->setOrganization($this);
        }

        return $this;
    }

    public function removeOrganizationAddress(OrganizationAddress $organizationAddress): static
    {
        if ($this->organizationAddresses->removeElement($organizationAddress)) {
            // set the owning side to null (unless already changed)
            if ($organizationAddress->getOrganization() === $this) {
                $organizationAddress->setOrganization(null);
            }
        }

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): static
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * @return Collection<int, OrganizationMessenger>
     */
    public function getMessengers(): Collection
    {
        return $this->messengers;
    }

    public function addMessenger(OrganizationMessenger $messenger): static
    {
        if (!$this->messengers->contains($messenger)) {
            $this->messengers->add($messenger);
            $messenger->setOrganization($this);
        }

        return $this;
    }

    public function removeMessenger(OrganizationMessenger $messenger): static
    {
        if ($this->messengers->removeElement($messenger)) {
            // set the owning side to null (unless already changed)
            if ($messenger->getOrganization() === $this) {
                $messenger->setOrganization(null);
            }
        }

        return $this;
    }
}

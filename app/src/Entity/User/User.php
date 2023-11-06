<?php

namespace App\Entity\User;

use App\Entity\Appeal\Appeal;
use App\Entity\Appeal\AppealMessage;
use App\Entity\Embeddable\Hash;
use App\Entity\Organization\Organization;
use App\Entity\RoleGroup;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embedded;
use libphonenumber\PhoneNumber;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    const RESEND_CODE_TIME_RESTRICTION = 60;
    const FAILED_CODE_REQUEST_MESSAGE = "Approve code request failed";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $surname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $middleName = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $birthday = null;

    #[ORM\Column(nullable: true)]
    private ?bool $sex = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $approveEmailCode;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $approveEmailCodeReceived;

    #[ORM\Column(options: ['default' => 0])]
    private ?bool $confirmEmail = false;

    #[ORM\Column(type: "phone_number", length: 255, unique: true)]
    private ?PhoneNumber $phone = null;


    #[MaxDepth(1)]
    #[ORM\ManyToMany(mappedBy: 'users', targetEntity: RoleGroup::class,  cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Collection $roleGroups;

    #[Ignore]
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?UserPriceType $priceType = null;

    #[ORM\Column]
    private ?bool $exchangeRate = false;

    #[Ignore]
    #[Embedded(class: Hash::class)]
    private Hash $passwordRecoveryHash;

    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?UserManager $manager = null;

    #[ORM\Column(options: ['default' => 0])]
    private ?bool $deleted = false;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $invitedBy = null;


    public function __construct()
    {
        $this->roleGroups = new ArrayCollection();
        $this->passwordRecoveryHash = new Hash();
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

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(?string $middleName): static
    {
        $this->middleName = $middleName;

        return $this;
    }

    public function getBirthday(): ?DateTimeImmutable
    {
        return $this->birthday;
    }

    public function setBirthday(?DateTimeImmutable $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function isSex(): ?bool
    {
        return $this->sex;
    }

    public function setSex(bool $sex): static
    {
        $this->sex = $sex;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isConfirmEmail(): ?bool
    {
        return $this->confirmEmail;
    }

    public function setConfirmEmail(bool $confirmEmail): static
    {
        $this->confirmEmail = $confirmEmail;

        return $this;
    }

    public function getPhone(): ?PhoneNumber
    {
        return $this->phone;
    }

    public function setPhone(PhoneNumber $phone): static
    {
        $this->phone = $phone;

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

    public function setUpdatedAt(DateTimeImmutable $updatedAt): static
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
            $organization->setOwner($this);
        }

        return $this;
    }

    public function removeOrganization(Organization $organization): static
    {
        if ($this->organizations->removeElement($organization)) {
            // set the owning side to null (unless already changed)
            if ($organization->getOwner() === $this) {
                $organization->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Organization>
     */
    public function getOrganizationsEmployee(): Collection
    {
        return $this->organizationsEmployee;
    }

    public function addOrganizationEmployee(Organization $organization): static
    {
        if (!$this->organizationsEmployee->contains($organization)) {
            $this->organizationsEmployee->add($organization);
            $organization->setEmployee($this);
        }

        return $this;
    }

    public function removeOrganizationEmployee(Organization $organization): static
    {
        if ($this->organizationsEmployee->removeElement($organization)) {
            // set the owning side to null (unless already changed)
            if ($organization->getEmployee() === $this) {
                $organization->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RoleGroup>
     */
    public function getRoleGroups(): Collection
    {
        return $this->roleGroups;
    }

    public function addRoleGroup(RoleGroup $roleGroup): static
    {
        if (!$this->roleGroups->contains($roleGroup)) {
            $this->roleGroups->add($roleGroup);
            $roleGroup->addUser($this);
        }

        return $this;
    }

    public function removeRoleGroup(RoleGroup $roleGroup): static
    {
        if ($this->roleGroups->removeElement($roleGroup)) {
            $roleGroup->removeUser($this);
        }

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }


    public function getSurname(): ?string
    {
        return $this->surname;
    }


    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        foreach ($this->getRoleGroups() as $group) {
            foreach ($group->getRoles() as $role) {
                $roles[] = $role->getName();
            }
        }

        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
//        $this->password = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }


    public function getApproveEmailCode(): ?string
    {
        return $this->approveEmailCode;
    }

    public function setApproveEmailCode(?string $approveEmailCode): void
    {
        $this->approveEmailCode = $approveEmailCode;
    }

    public function getApproveEmailCodeReceived(): ?DateTimeImmutable
    {
        return $this->approveEmailCodeReceived;
    }

    public function setApproveEmailCodeReceived(?DateTimeImmutable $approveEmailCodeReceived): void
    {
        $this->approveEmailCodeReceived = $approveEmailCodeReceived;
    }

    public function getTypePrice(): ?UserPriceType
    {
        return $this->priceType;
    }

    public function setTypePrice(?UserPriceType $priceType): static
    {
        $this->priceType = $priceType;

        return $this;
    }

    public function isExchangeRate(): ?bool
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(bool $exchangeRate): static
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }

    public function getPasswordRecoveryHash(): ?Hash
    {
        return $this->passwordRecoveryHash;
    }

    public function setPasswordRecoveryHash(?Hash $hash): static
    {
        $this->passwordRecoveryHash = $hash;

        return $this;
    }

    public function getManager(): ?UserManager
    {
        return $this->manager;
    }

    public function setManager(?UserManager $manager): static
    {
        $this->manager = $manager;

        return $this;
    }

    public function getInvitedBy(): ?int
    {
        return $this->invitedBy;
    }

    public function setInvitedBy(?int $invitedBy): static
    {
        $this->invitedBy = $invitedBy;

        return $this;
    }

    public function addOrganizationsEmployee(Organization $organizationsEmployee): static
    {
        if (!$this->organizationsEmployee->contains($organizationsEmployee)) {
            $this->organizationsEmployee->add($organizationsEmployee);
            $organizationsEmployee->setEmployee($this);
        }

        return $this;
    }

    public function removeOrganizationsEmployee(Organization $organizationsEmployee): static
    {
        if ($this->organizationsEmployee->removeElement($organizationsEmployee)) {
            // set the owning side to null (unless already changed)
            if ($organizationsEmployee->getEmployee() === $this) {
                $organizationsEmployee->setEmployee(null);
            }
        }

        return $this;
    }

    public function getPriceType(): ?UserPriceType
    {
        return $this->priceType;
    }

    public function setPriceType(?UserPriceType $priceType): static
    {
        $this->priceType = $priceType;

        return $this;
    }

    /**
     * @return Collection<int, Appeal>
     */
    public function getAppeals(): Collection
    {
        return $this->appeals;
    }

    public function addAppeal(Appeal $appeal): static
    {
        if (!$this->appeals->contains($appeal)) {
            $this->appeals->add($appeal);
            $appeal->setUser($this);
        }

        return $this;
    }

    public function removeAppeal(Appeal $appeal): static
    {
        if ($this->appeals->removeElement($appeal)) {
            // set the owning side to null (unless already changed)
            if ($appeal->getUser() === $this) {
                $appeal->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AppealMessage>
     */
    public function getAppealMessages(): Collection
    {
        return $this->appealMessages;
    }

    public function addAppealMessage(AppealMessage $appealMessage): static
    {
        if (!$this->appealMessages->contains($appealMessage)) {
            $this->appealMessages->add($appealMessage);
            $appealMessage->setSender($this);
        }

        return $this;
    }

    public function removeAppealMessage(AppealMessage $appealMessage): static
    {
        if ($this->appealMessages->removeElement($appealMessage)) {
            // set the owning side to null (unless already changed)
            if ($appealMessage->getSender() === $this) {
                $appealMessage->setSender(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getEmail();
    }
}


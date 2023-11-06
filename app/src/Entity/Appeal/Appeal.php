<?php

namespace App\Entity\Appeal;

use App\Entity\User\User;
use App\Repository\AppealRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: AppealRepository::class)]
class Appeal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'appeals')]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'appeals')]
    private ?AppealCategory $category = null;

    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'appeals')]
    private ?AppealStatus $status = null;

    #[MaxDepth(1)]
    #[ORM\OneToMany(mappedBy: 'appeal', targetEntity: AppealMessage::class, cascade: ['persist'])]
    private Collection $appealMessages;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->appealMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?AppealCategory
    {
        return $this->category;
    }

    public function setCategory(?AppealCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getStatus(): ?AppealStatus
    {
        return $this->status;
    }

    public function setStatus(?AppealStatus $status): static
    {
        $this->status = $status;

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
            $appealMessage->setAppeal($this);
        }

        return $this;
    }

    public function removeAppealMessage(AppealMessage $appealMessage): static
    {
        if ($this->appealMessages->removeElement($appealMessage)) {
            // set the owning side to null (unless already changed)
            if ($appealMessage->getAppeal() === $this) {
                $appealMessage->setAppeal(null);
            }
        }

        return $this;
    }
}

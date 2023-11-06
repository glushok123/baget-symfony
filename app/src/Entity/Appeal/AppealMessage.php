<?php

namespace App\Entity\Appeal;

use App\Entity\User\User;
use App\Repository\AppealMessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use DateTimeImmutable;


#[ORM\Entity(repositoryClass: AppealMessageRepository::class)]
class AppealMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'appealMessages')]
    private ?Appeal $appeal = null;

    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'appealMessages')]
    private ?User $sender = null;

    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'appealMessages')]
    private ?User $addressee = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column()]
    private ?\DateTimeImmutable $createdAt = null;

    #[MaxDepth(1)]
    #[ORM\OneToMany(mappedBy: 'appealMessage', targetEntity: AppealMessageFile::class)]
    private Collection $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
        if ($this->createdAt == null) $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppeal(): ?Appeal
    {
        return $this->appeal;
    }

    public function setAppeal(?Appeal $appeal): static
    {
        $this->appeal = $appeal;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getAddressee(): ?User
    {
        return $this->addressee;
    }

    public function setAddressee(?User $addressee): static
    {
        $this->addressee = $addressee;

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

    /**
     * @return Collection<int, AppealMessageFile>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(AppealMessageFile $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setAppealMessage($this);
        }

        return $this;
    }

    public function removeFile(AppealMessageFile $file): static
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getAppealMessage() === $this) {
                $file->setAppealMessage(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getCreatedAt()->format('d.m.Y H:i');
    }
}

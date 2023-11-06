<?php

namespace App\Entity\Appeal;

use App\Repository\AppealMessageFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: AppealMessageFileRepository::class)]
class AppealMessageFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[MaxDepth(1)]
    #[ORM\ManyToOne(inversedBy: 'files')]
    private ?AppealMessage $appealMessage = null;

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

    public function getAppealMessage(): ?AppealMessage
    {
        return $this->appealMessage;
    }

    public function setAppealMessage(?AppealMessage $appealMessage): static
    {
        $this->appealMessage = $appealMessage;

        return $this;
    }
}

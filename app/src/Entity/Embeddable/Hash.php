<?php

namespace App\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;
use DateTimeImmutable;

#[Embeddable]
class Hash
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $value = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $createdAt = null;


    public function getValue(): ?string
    {
        return $this->value;
    }


    public function setValue(?string $value): void
    {
        $this->value = $value;
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
}

<?php

namespace App\Entity\Appeal;

use App\Repository\AppealStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: AppealStatusRepository::class)]
class AppealStatus
{
    const STATUS_NEW = 'Новое обращение';
    const STATUS_ANSWER_MANAGER = 'Ответ службы поддержки';
    const STATUS_ANSWER_USER = 'Ответ клиента';
    const STATUS_CLOSE = 'Закрыто';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[MaxDepth(1)]
    #[ORM\OneToMany(mappedBy: 'status', targetEntity: Appeal::class)]
    private Collection $appeals;

    public function __construct()
    {
        $this->appeals = new ArrayCollection();
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
            $appeal->setStatus($this);
        }

        return $this;
    }

    public function removeAppeal(Appeal $appeal): static
    {
        if ($this->appeals->removeElement($appeal)) {
            // set the owning side to null (unless already changed)
            if ($appeal->getStatus() === $this) {
                $appeal->setStatus(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}

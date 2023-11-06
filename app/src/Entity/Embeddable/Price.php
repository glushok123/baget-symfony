<?php

namespace App\Entity\Embeddable;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;
use Symfony\Component\Serializer\Annotation\Groups;

#[Embeddable]
class Price
{
    #[Groups(['searchable'])]
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0', nullable: true)]
    private ?string $rubValue = null;

    #[Groups(['searchable'])]
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0', nullable: true)]
    private ?string $usdValue = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0', nullable: true)]
    private ?string $rubTransitValue = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0', nullable: true)]
    private ?string $usdTransitValue = null;

    public function getRubValue (): ?string
    {
        return $this->rubValue ;
    }

    public function setRubValue (string $rubValue): static
    {
        $this->rubValue  = $rubValue ;

        return $this;
    }

    public function getRubTransitValue (): ?string
    {
        return $this->rubTransitValue ;
    }

    public function setRubTransitValue (string $rubTransitValue): static
    {
        $this->rubTransitValue  = $rubTransitValue ;

        return $this;
    }

    public function getUsdValue (): ?string
    {
        return $this->usdValue ;
    }

    public function setUsdValue (string $usdValue): static
    {
        $this->usdValue  = $usdValue ;

        return $this;
    }

    public function getUsdTransitValue (): ?string
    {
        return $this->usdTransitValue ;
    }

    public function setUsdTransitValue (string $usdTransitValue): static
    {
        $this->usdTransitValue  = $usdTransitValue ;

        return $this;
    }
}

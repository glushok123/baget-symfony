<?php

namespace App\Dto\User;

use App\Dto\BasicDto;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UserPriceTypeDto extends BasicDto
{
    public function __construct(
        #[Groups(['price_name'])]
        #[Assert\Choice(['rub', 'rub/usd', 'usd'])]
        #[OA\Property(description: 'rub, rub/usd, usd')]
        public readonly string          $name,
        public readonly ?int            $id = null,
    )
    {
    }
}

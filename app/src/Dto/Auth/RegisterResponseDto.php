<?php

namespace App\Dto\Auth;

use App\Dto\BasicDto;
use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;
use App\Dto\Organization\OrganizationDto;
use App\Dto\User\UserDto;

class RegisterResponseDto extends BasicDto implements DtoInterface
{
    public function __construct(
        public readonly UserDto $user, //todo: вероятно, удалить все, кроме токена и рефреш токена
        public readonly OrganizationDto $organization,

        #[OA\Property(
            description: 'Dadata response',
            type: 'array',
            items: new OA\Items(type: 'object')
        )]
        public readonly array $organizationDadata = []
    )
    {
    }
}
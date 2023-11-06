<?php

namespace App\Dto\Appeal;

use App\Dto\Appeal\AppealCategory\AppealCategoryDto;
use App\Dto\Appeal\AppealMessage\AppealMessageDto;
use App\Dto\Appeal\AppealMessageFile\AppealMessageFileDto;
use App\Dto\Appeal\AppealStatus\AppealStatusDto;
use App\Dto\BasicDto;
use App\Dto\User\UserDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class AppealDto extends BasicDto
{
    public function __construct(
        #[Groups(['appeal'])]
        public readonly ?int                $id = null,

        #[Groups(['create_appeal', 'appeal'])]
        #[Assert\NotBlank(groups: ['create_appeal'])]
        public readonly ?string             $name = null,

        public readonly ?UserDto            $user = null,

        #[Groups(['create_appeal', 'appeal'])]
        #[Assert\NotBlank(groups: ['create_appeal'])]
        public readonly ?AppealCategoryDto  $category = null,

        #[Groups(['appeal'])]
        public readonly ?AppealStatusDto    $status  = null,

        #[Groups(['appeal'])]
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: AppealMessageDto::class)))]
        public readonly array               $appealMessages = [],

        #[Groups(['create_appeal'])]
        #[Assert\NotBlank(groups: ['create_appeal'])]
        public readonly ?string             $message = null,

        #[Groups(['create_appeal'])]
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: AppealMessageFileDto::class)))]
        public readonly array               $files = [],

        #[Groups(['appeal'])]
        public readonly ?DateTimeImmutable $createdAt = null,

        #[Groups(['appeal'])]
        public readonly ?DateTimeImmutable $updatedAt = null,
    )
    {
    }
}

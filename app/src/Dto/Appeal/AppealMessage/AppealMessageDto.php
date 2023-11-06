<?php

namespace App\Dto\Appeal\AppealMessage;

use App\Dto\Appeal\AppealMessageFile\AppealMessageFileDto;
use App\Dto\BasicDto;
use App\Dto\User\UserDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use DateTimeImmutable;
use Symfony\Component\Serializer\Annotation\Groups;


class AppealMessageDto extends BasicDto
{
    public function __construct(
        #[Groups(['appeal'])]
        public readonly ?int                $id = null,

        #[Groups(['appeal'])]
        public readonly ?string             $message = null,

        #[Groups(['appeal'])]
        public readonly ?UserDto            $sender = null,

        #[Groups(['appeal'])]
        public readonly ?UserDto            $addressee = null,

        #[Groups(['appeal'])]
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: AppealMessageFileDto::class)))]
        public readonly array               $files = [],

        #[Groups(['appeal'])]
        public readonly ?DateTimeImmutable $createdAt = null,
    )
    {
    }
}

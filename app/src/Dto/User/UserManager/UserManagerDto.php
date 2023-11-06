<?php

namespace App\Dto\User\UserManager;

use App\Dto\BasicDto;
use libphonenumber\PhoneNumber;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as PhoneNumberAssert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

class UserManagerDto extends BasicDto
{
    public function __construct(
        #[Groups(['manager_id'])]
        public readonly ?int               $id = null,
        public readonly ?string            $name = null,

        #[PhoneNumberAssert]
        #[OA\Property(type: 'string', example: '+79991234567')]
        public readonly ?PhoneNumber       $phone = null,

        #[Assert\Email]
        public readonly ?string            $email = null,
        public readonly ?bool              $deleted = null,
    )
    {
    }
}

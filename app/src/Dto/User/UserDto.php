<?php

namespace App\Dto\User;

use App\Dto\BasicDto;
use App\Dto\Organization\OrganizationDto;
use DateTimeImmutable;
use libphonenumber\PhoneNumber;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as PhoneNumberAssert;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Dto\User\UserManager\UserManagerDto;
use Symfony\Component\Serializer\Annotation\Groups;

class UserDto extends BasicDto
{
    public function __construct(
        #[Groups(['invitation_user', 'create_user', 'update_user'])]
        #[Assert\NotBlank(groups: ['invitation_user', 'create_user'])]
        #[Assert\Regex(
            pattern: "/^[\s]|[\s]$/iu",
            match: false,
            groups: ['invitation_user', 'update_user', 'create_user'],
            message: 'Фамилия не должна содержать пробелов в начале и конце строки',
        )]
        #[Assert\Expression(
            "value !== ''",
            message: 'Фамилия не может быть пустой',
            groups: ['invitation_user', 'update_user', 'create_user']
        )]
        public readonly ?string            $surname = null,

        #[Groups(['invitation_user', 'create_user', 'update_user'])]
        #[Assert\NotBlank(groups: ['invitation_user', 'create_user'])]
        #[Assert\Regex(
            pattern: "/^[\s]|[\s]$/iu",
            match: false,
            groups: ['invitation_user', 'update_user', 'create_user'],
            message: 'Имя не должно содержать пробелов в начале и конце строки',
        )]
        #[Assert\Expression(
            "value !== ''",
            message: 'Имя не может быть пустым',
            groups: ['invitation_user', 'update_user', 'create_user']
        )]
        public readonly ?string            $name = null,

        #[Groups(['invitation_user', 'create_user', 'update_user'])]
        #[Assert\Regex(
            pattern: "/^[\s]|[\s]$/iu",
            match: false,
            groups: ['invitation_user', 'update_user', 'create_user'],
            message: 'Отчество не должно содержать пробелов в начале и конце строки',
        )]
        public readonly ?string            $middleName = null,

        #[Groups(['create_user', 'invitation_user', 'update_user'])]
        #[PhoneNumberAssert(groups: ['invitation_user', 'update_user', 'create_user'])]
        #[OA\Property(type: 'string', example: '+79991234567')]
        #[Assert\NotBlank(groups: ['invitation_user', 'create_user'])]
        public readonly ?PhoneNumber       $phone = null,

        #[Groups(['invitation_user', 'user_email', 'create_user'])]
        #[Assert\Email(groups: ['invitation_user', 'update_user', 'create_user'])]
        #[Assert\NotBlank(groups: ['invitation_user', 'create_user'])]
        #[Assert\Expression(
            "value !== ''",
            message: 'Email не может быть пустым',
            groups: ['update_user', 'create_user']
        )]
        public readonly ?string            $email = null,

        #[Groups(['invitation_user', 'create_user', 'update_user'])]
        public readonly ?bool              $sex = null,
        public readonly ?bool              $confirmEmail = null,
        public readonly ?bool              $exchangeRate = null,

        #[Groups(['create_user', 'update_user'])]
        public readonly ?DateTimeImmutable $birthday = null,

        #[Groups(['create_user', 'update_user'])]
        public readonly ?UserPriceTypeDto  $priceType = null,

        public readonly ?UserManagerDto    $manager = null,

        #[Groups(['user_id', 'appeal'])]
        #[Assert\NotBlank(groups: ['user_id'])]
        public readonly ?int               $id = null,

        #[Groups(['password_user'])]
        public readonly ?string            $password = null,
        public readonly ?string            $token = null,
        public readonly ?string            $refreshToken = null,
        public readonly ?string            $passwordRecoveryHash = null,

        #[Groups(['deleted_user'])]
        public readonly ?bool              $deleted = null,

        #[Groups(['invitation_user', 'create_user'])]
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: OrganizationDto::class)))]
        public readonly array              $organizations = [],

        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: OrganizationDto::class)))]
        public readonly array              $organizationsEmployee = [],
    )
    {
    }
}

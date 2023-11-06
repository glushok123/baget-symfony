<?php

namespace App\Action\Auth;

use App\Dto\Profile\OrganizationEditDto;
use App\Dto\Profile\RegisterFinalRequestDto;
use App\Dto\Profile\ProfileEditResponseDto;
use App\Dto\Profile\UserEditDto;
use App\Service\OrganizationService;
use App\Service\UserService;

class ProfileEditAction
{
    public function __construct(
        private readonly UserService $userService,
        private readonly OrganizationService $organizationService
    )
    {
    }

    public function handle(RegisterFinalRequestDto $dto): ProfileEditResponseDto
    {
        $userDto = new UserEditDto(
            surname: $dto->surname,
            name: $dto->name,
            middleName: $dto->middleName,
            phone: $dto->phone,
            email: $dto->email,
            sex: $dto->sex,
            birthday: $dto->birthday
        );

        $organizationDto = new OrganizationEditDto(
            inn: $dto->inn,
            organizationName: $dto->organizationName,
            legalAddress: $dto->legalAddress,
            description: $dto->description,
            publicType: $dto->publicType,
            organizationPhone: $dto->organizationPhone,
            site: $dto->site,
            messengers: $dto->messengers
        );
        $user = $this->userService->update($userDto);
        $organization = $this->organizationService->update($organizationDto);

        return new ProfileEditResponseDto();
    }
}
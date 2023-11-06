<?php

namespace App\Action\Auth;

use App\Dto\Auth\RegisterResponseDto;
use App\Dto\Auth\RegisterRequestDto;
use App\Repository\OrganizationRepository;
use App\Repository\UserRepository;
use App\Service\OrganizationService;
use App\Service\UserService;
use Exception;
use App\Repository\CountryRepository;
use App\Validator\InnValidator;
use App\Validator\Inn;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegisterFinalAction
{
    const STATUS_ACTIVE = 'ACTIVE';

    public function __construct(
        private readonly UserService                $userService,
        private readonly OrganizationService        $organizationService,
        private readonly CountryRepository          $countryRepository,
        private readonly InnValidator               $validatorInn,
        private readonly TranslatorInterface        $translator,
        private readonly OrganizationRepository     $organizationRepository,
        private readonly UserRepository             $userRepository,
    )
    {
    }

    public function handle(RegisterRequestDto $dto): RegisterResponseDto
    {
        $dadata = [];

        if (!$this->organizationService->needToCreate($dto->organization->inn)) {
            throw new Exception($this->translator->trans('organization_exists_user_could_not_be_created'));
        }

        if ($this->organizationRepository->findOneBy(['phone' => $dto->organization->phone])) {
            throw new Exception($this->translator->trans('phone_already_exists'), 500);
        }

        if ($this->userRepository->findOneBy(['phone' => $dto->organization->phone])) {
            throw new Exception($this->translator->trans('phone_already_exists'), 500);
        }

        if ($dto->organization->country->id == $this->countryRepository->findOneBy(['name' => "Россия"])->getId()) {
            $this->validatorInn->validate($dto->organization->inn, new Inn($dto->organization->inn));
            $dadata =  $this->organizationService->getDadata($dto->organization->inn);
            $status = empty($dadata) ? null : $dadata['data']['state']['status'];

            if ($status != self::STATUS_ACTIVE){
                throw new Exception($this->translator->trans('organization_no_active'), 500);
            }
        }

        $userDto = $this->userService->registerUser($dto->user);
        $organizationDto = $this->organizationService->register($dto->organization, $userDto, $dadata);

        return new RegisterResponseDto(
            user: $userDto,
            organization: $organizationDto,
            organizationDadata: $dadata
        );
    }
}

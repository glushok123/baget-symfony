<?php

namespace App\Service;

use App\Dto\Auth\CheckCodeRequestDto;
use App\Dto\Auth\LoginUserRequestDto;
use App\Dto\Auth\ReceiveCodeRequestDto;
use App\Dto\Auth\RegisterUserDto;
use App\Dto\Auth\UserRecoveryPasswordDto;
use App\Dto\Organization\OrganizationFilterDto;
use App\Dto\User\UserChangePasswordDto;
use App\Dto\User\UserDto;
use App\Entity\Embeddable\Hash;
use App\Entity\RefreshToken;
use App\Entity\RoleGroup;
use App\Entity\User\User;
use App\Entity\User\UserPriceType;
use App\Repository\CountryRepository;
use App\Repository\OrganizationRepository;
use App\Repository\OrganizationTypeRepository;
use App\Repository\RefreshTokenRepository;
use App\Repository\RoleGroupRepository;
use App\Repository\UserPriceTypeRepository;
use App\Repository\UserRepository;
use App\Service\Notification\NotificationInterface;
use App\Service\Notification\NotificationService;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Annotations\AnnotationReader;
use Exception;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserService
{
    const REFRESH_TTL = 2592000;
    private $serializer;
    public function __construct(
        private readonly UserRepository                 $repository,
        private readonly UserPasswordHasherInterface    $hasher,
        private readonly JWTTokenManagerInterface       $tokenManager,
        private readonly RefreshTokenGeneratorInterface $refreshTokenManager,
        private readonly JWTEncoderInterface            $encoder,
        private readonly NotificationInterface          $notification,
        private readonly RefreshTokenRepository         $refreshTokenRepository,
        private readonly UserPriceTypeRepository        $userPriceTypeRepository,
        private readonly TranslatorInterface            $translator,
        private readonly OrganizationRepository         $organizationRepository,
        private readonly CountryRepository              $countryRepository,
        private readonly OrganizationTypeRepository     $organizationTypeRepository,
        private readonly NotificationService            $notificationService,
        private readonly TokenStorageInterface          $tokenStorageInterface,
        private readonly RoleGroupRepository            $roleGroupRepository,
        private readonly OrganizationService            $organizationService,
        private readonly ValidatorInterface             $validator
    )
    {
        $metadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new PropertyNormalizer($metadataFactory, null);

        $this->serializer = new Serializer([$normalizer, new DateTimeNormalizer(), new ObjectNormalizer()]);
    }


    public function registerUser(RegisterUserDto $dto): UserDto
    {
        if ($this->repository->findOneBy(['email' => $dto->email]) || $this->repository->findOneBy(['phone' => $dto->phone])) {
            throw new Exception($this->translator->trans('user_already_exists'), 500);
        }

        $user = new User();
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setName($dto->name);
        $user->setSurname($dto->surname);
        $user->setMiddleName($dto->middleName);
        $user->setEmail($dto->email);
        $user->setPhone($dto->phone);
        $user->setSex($dto->sex);
        $user->setBirthday($dto->birthday);
        $user->setTypePrice($this->userPriceTypeRepository->findOneBy(['name' => UserPriceType::NAME_TYPE_PRICE_RUBLE]));
        $user->setPassword($this->hasher->hashPassword($user, $dto->password));
        $token = $this->tokenManager->create($user);
        $refreshToken = $this->refreshTokenManager->createForUserWithTtl($user, self::REFRESH_TTL);
        $this->storeRefreshToken($user, $refreshToken);
        $user->addRoleGroup($this->roleGroupRepository->findOneBy(['name'=> RoleGroup::NAME_ROLE_GROUP_OWNER]));
        $this->repository->save($user);


        return $this->serializerUser($user, [], [] , ['token' => $token, 'refreshToken' => $refreshToken]);
    }

    public function loginUser(LoginUserRequestDto $dto): string // если что, этот метод не работает :) отрабатывает бандловская авторизация
    {
        $user = $this->repository->findOneBy(['email' => $dto->email]);

        if (empty($user)) {
            throw new Exception($this->translator->trans('user_could_not_be_found'));
        }

        if ($user->isDeleted()) {
            throw new Exception($this->translator->trans('user_block'));
        }

        return $this->tokenManager->create($user);
    }

    public function remove(User $user): void
    {
        $this->repository->remove($user);
    }

    public function generateCode(ReceiveCodeRequestDto $dto): DateTimeImmutable
    {
        $user = $this->repository->findOneBy(['email' => $dto->email]);
        if (!empty($user->getApproveEmailCodeReceived())) {
            $interval = (new DateTimeImmutable())->getTimestamp() - $user->getApproveEmailCodeReceived()->getTimestamp();
        }
        if (!empty($interval) && $interval < User::RESEND_CODE_TIME_RESTRICTION) {
            throw new Exception(User::FAILED_CODE_REQUEST_MESSAGE, 500);
        }
        $code = (string)random_int(100000, 999999);
        $user->setApproveEmailCode($code);
        $user->setApproveEmailCodeReceived(new DateTimeImmutable());
        $this->repository->save($user);

        return $user->getApproveEmailCodeReceived();
    }

    public function checkCode(CheckCodeRequestDto $dto): bool
    {
        $user = $this->repository->findOneBy(['email' => $dto->email]);
        if ($user->isConfirmEmail()) {
            return $this->translator->trans('email_approved');
        }
        if (!empty($user->getApproveEmailCode()) && $user->getApproveEmailCode() === $dto->code) {
            $user->setConfirmEmail(true);
            $this->repository->save($user);
            return true;
        }

        return false;
    }

    private function sendCode($code)
    {
        $this->notification->send();
    }

    public function create(UserDto $dto): UserDto
    {
        if ($this->repository->findOneBy(['email' => $dto->email])) {
            throw new Exception($this->translator->trans('user_already_exists'), 500);
        }

        $user = new User();
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setName($dto->name);
        $user->setSurname($dto->surname);
        $user->setMiddleName($dto->middleName);
        $user->setEmail($dto->email);
        $user->setPhone($dto->phone);
        $user->setSex($dto->sex);
        $user->setBirthday($dto->birthday);
        $user->setExchangeRate($dto->exchangeRate ?? false);
        $user->setPassword($this->hasher->hashPassword($user, $dto->password));
        $user->setTypePrice(!empty($dto->priceType) ? $this->userPriceTypeRepository->findOneBy(['name' => $dto->priceType->name]) : $this->userPriceTypeRepository->findOneBy(['name' => UserPriceType::NAME_TYPE_PRICE_RUBLE]));
        $this->repository->save($user);

        return $this->serializerUser($user);
    }

    public function delete(?User $user): UserDto
    {
        if (empty($user)) {
            throw new Exception($this->translator->trans('user_could_not_be_found'));
        }

        $user->setDeleted(1);

        foreach($user->getOrganizations() as $organization) {
            $organization->setDeleted(1);
            if (!empty($organization->getEmployee())) {
                $organization->getEmployee()->setDeleted(1);
            }
        }

        foreach($user->getOrganizationsEmployee() as $organization) {
            $organization->setEmployee(null);
        }

        $this->repository->save($user);

        return new UserDto(
            id: $user->getId(),
            deleted: true
        );
    }

    public function update(UserDto $dto, ?User $user): UserDto
    {
        if (empty($user)) {
            throw new Exception($this->translator->trans('user_could_not_be_found'));
        }

        if (!empty($dto->email) && $dto->email != $user->getEmail()) {
            $user->setConfirmEmail(false);
            $user->setApproveEmailCode(null);
        }

        $user->setName($dto->name ?? $user->getName());
        $user->setSurname($dto->surname ?? $user->getSurname());
        $user->setMiddleName($dto->middleName ?? $user->getMiddleName());
        $user->setPhone($dto->phone ?? $user->getPhone());
        $user->setEmail($dto->email ?? $user->getEmail());
        $user->setSex($dto->sex ?? $user->isSex());
        $user->setBirthday($dto->birthday ?? $user->getBirthday());
        $user->setExchangeRate($dto->exchangeRate ?? $user->isExchangeRate());
        $user->setTypePrice(!empty($dto->priceType) ? $this->userPriceTypeRepository->findOneBy(['name' => $dto->priceType->name]) : $this->userPriceTypeRepository->findOneBy(['id' => $user->getTypePrice()->getId()]));

        $user->setUpdatedAt(new DateTimeImmutable());
        $token = $this->tokenManager->create($user);
        $refreshToken = $this->refreshTokenManager->createForUserWithTtl($user, self::REFRESH_TTL);
        $this->repository->save($user);

        if (!empty($dto->organizations)){
            $this->setEmployeeOrganization($dto->organizations, $user);
        }

        return $this->serializerUser($user, [], [] , ['token' => $token, 'refreshToken' => $refreshToken]);
    }

    public function changePasswordUser(UserChangePasswordDto $dto, ?User $user): UserDto
    {
        if (empty($user)) {
            throw new Exception($this->translator->trans('user_could_not_be_found'));
        }

        if ($dto->password != $dto->passwordConfirm) {
            throw new Exception($this->translator->trans('password_no_match'));
        }

        if (!$this->hasher->isPasswordValid($user, $dto->oldPassword)) {
            throw new Exception($this->translator->trans('password_no_matches_with_bd'));
        }

        $user->setPassword($dto->password ? $this->hasher->hashPassword($user, $dto->password) : $user->getPassword());
        $user->setUpdatedAt(new DateTimeImmutable());

        $this->repository->save($user);

        return $this->serializerUser($user);
    }

    private function setEmployeeOrganization(array $organizations, User $user): void
    {
        if (!empty($this->organizationRepository->findBy(['employee'=> $user]))){
            foreach($this->organizationRepository->findBy(['employee'=> $user]) as $organization) {
                $organization->setEmployee(null);
                $this->organizationRepository->save($organization);
            }
        }

        foreach ($organizations as $organizationDto) {
            $organization = $this->organizationRepository->findOneBy(['id' => $organizationDto['id']]);

            if (empty($organization)) {
                continue;
            }

            $organization->setEmployee($user);
            $this->organizationRepository->save($organization);
        }
    }

    public function getCollection(): array
    {
        $arrayUserDto = [];

        $users = $this->repository->findBy([
            'deleted' => 0
        ]);

        foreach ($users as $user) {
            $userDto = $this->serializerUser($user);
            $arrayUserDto[] = $userDto->toArray();
        }

        return $arrayUserDto;
    }

    public function getUser(UserDto $dto): UserDto
    {
        if (!empty($dto->email)) {
            $user = $this->repository->findOneBy(['email' => $dto->email]);
        }

        if (!empty($dto->id)) {
            $user = $this->repository->findOneBy(['id' => $dto->id]);
        }

        if (!empty($dto->passwordRecoveryHash)) {
            $user = $this->repository->findOneBy(['passwordRecoveryHash.value' => $dto->passwordRecoveryHash]);
        }

        if (empty($user)) {
            throw new Exception($this->translator->trans('user_could_not_be_found'));
        }

        return $this->serializerUser($user);
    }

    public function getAllOrganizationUser(?User $user, ?OrganizationFilterDto $organizationFilterDto, array $organizations = []): array
    {
        if (empty($user)) {
            throw new Exception($this->translator->trans('user_could_not_be_found'));
        }

        foreach($user->getOrganizations() as $organization) {
            if ($organization->isDeleted()) {
                continue;
            }

            if (!empty($organizationFilterDto) && $organizationFilterDto->withoutEmployee && !empty($organization->getEmployee())) {
                continue;
            }

            $organizations[] = $this->organizationService->serializerOrganization($organization)->toArray();
        }

        return $organizations;
    }

    public function getUserByToken(?User $user): UserDto
    {
        if (empty($user)) {
            throw new Exception($this->translator->trans('user_could_not_be_found'));
        }

        foreach($user->getOrganizations() as $organization) {
            if (!$organization->isApproved()) {
                $user->removeOrganization($organization);
            }
        }

        return $this->serializerUser($user);
    }

    private function storeRefreshToken(User $user, RefreshTokenInterface $refreshToken): void
    {
        $validThru = time() + self::REFRESH_TTL;
        $token = (new RefreshToken())
            ->setUsername($user->getEmail())
            ->setRefreshToken($refreshToken->getRefreshToken())
            ->setValid((new DateTime())->setTimestamp($validThru));

        $this->refreshTokenRepository->save($token);
    }

    public function invitationUser(UserDto $dto, User $owner): UserDto
    {
        if ($this->repository->findOneBy(['email' => $dto->email])) {
            throw new Exception($this->translator->trans('user_already_exists'), 500);
        }

        $user = new User();
        $user->setName($dto->name);
        $user->setSurname($dto->surname);
        $user->setMiddleName($dto->middleName);
        $user->setEmail($dto->email);
        $user->setPhone($dto->phone);
        $user->setTypePrice($this->userPriceTypeRepository->findOneBy(['name' => UserPriceType::NAME_TYPE_PRICE_RUBLE]));
        $user->setPassword($this->hasher->hashPassword($user, random_bytes(20)));
        $user->setInvitedBy($owner->getId());

        $hash = new Hash();
        $hash->setValue(md5(time() . random_bytes(20)));
        $hash->setCreatedAt(new DateTimeImmutable());

        $user->setPasswordRecoveryHash($hash);
        $user->setCreatedAt(new DateTimeImmutable());

        $user->addRoleGroup($this->roleGroupRepository->findOneBy(['name'=> RoleGroup::NAME_ROLE_GROUP_EMPLOYEE]));
        $this->repository->save($user);

        foreach ($dto->organizations as $organizationDto) {
            $organization = $this->organizationRepository->findOneBy(['id' => $organizationDto['id']]);

            if (empty($organization)) {
                continue;
            }

            $organization->setEmployee($user);
            $this->organizationRepository->save($organization);
        }

        $this->notificationService->send();

        return new UserDto(
            id: $user->getId(),
        );
    }

    public function recoveryPasswordUser(UserDto $dto): UserDto
    {
        if (!$this->repository->findOneBy(['email' => $dto->email])) {
            throw new Exception($this->translator->trans('user_could_not_be_found'));
        }

        $user = $this->repository->findOneBy(['email' => $dto->email]);

        $hash = new Hash();
        $hash->setValue(md5(time() . random_bytes(20)));
        $hash->setCreatedAt(new DateTimeImmutable());

        $user->setPasswordRecoveryHash($hash);
        $user->setCreatedAt(new DateTimeImmutable());
        $this->repository->save($user);

        $this->notificationService->send();

        return new UserDto(
            id: $user->getId(),
        );
    }

    public function changePasswordByHashUser(UserRecoveryPasswordDto $dto): UserDto
    {
        $user = $this->repository->findOneBy(['passwordRecoveryHash.value' => $dto->hash]);

        if (empty($user)) {
            throw new Exception($this->translator->trans('user_could_not_be_found'));
        }

        if ($dto->password != $dto->passwordConfirm) {
            throw new Exception($this->translator->trans('password_no_match'));
        }

        $hash = new Hash();
        $hash->setValue(null);

        $user->setPassword($dto->password ? $this->hasher->hashPassword($user, $dto->password) : $user->getPassword());
        $user->setUpdatedAt(new DateTimeImmutable());
        $user->setPasswordRecoveryHash($hash);

        $this->repository->save($user);

        return $this->serializerUser($user);
    }

    public function getCollectionEmployee(User $user, array $employees = []): array
    {
        foreach ($this->repository->findBy(['invitedBy' => $user->getId()]) as $employee) {
            if (in_array($this->serializerUser($employee, ['organizations'])->toArray(), $employees) || $employee->isDeleted()) {
                continue;
            }

            $employees[] = $this->serializerUser($employee, ['organizations'])->toArray();
        }

        return $employees;
    }

    public function updateEmployee(UserDto $dto, User $user): UserDto
    {
        if (!empty($dto->email)) {
            $employee = $this->repository->findOneBy(['email' => $dto->email]);
        }

        if (!empty($dto->id)) {
            $employee = $this->repository->findOneBy(['id' => $dto->id]);
        }

        if (empty($employee)) {
            throw new Exception($this->translator->trans('employee_could_not_be_found'));
        }

        if ($user->getId() != $employee->getInvitedBy()){
            throw new Exception($this->translator->trans('this_is_not_your_employee'));
        }

        if (!empty($dto->email) && $dto->email != $employee->getEmail()) {
            $employee->setConfirmEmail(false);
            $employee->setApproveEmailCode(null);
        }

        $employee->setName($dto->name ?? $employee->getName());
        $employee->setSurname($dto->surname ?? $employee->getSurname());
        $employee->setMiddleName($dto->middleName ?? $employee->getMiddleName());
        $employee->setPhone($dto->phone ?? $employee->getPhone());
        $employee->setEmail($dto->email ?? $employee->getEmail());

        $employee->setUpdatedAt(new DateTimeImmutable());
        $this->repository->save($employee);
        $this->setEmployeeOrganization($dto->organizations, $employee);

        return $this->serializerUser($employee);
    }

    public function deleteEmployee(UserDto $dto, User $user): UserDto
    {
        if (!empty($dto->email)) {
            $employee = $this->repository->findOneBy(['email' => $dto->email]);
        }

        if (!empty($dto->id)) {
            $employee = $this->repository->findOneBy(['id' => $dto->id]);
        }

        if (empty($employee)) {
            throw new Exception($this->translator->trans('employee_could_not_be_found'));
        }

        if ($user->getId() != $employee->getInvitedBy()){
            throw new Exception($this->translator->trans('this_is_not_your_employee'));
        }

        $employee->setDeleted(1);

        foreach ($employee->getOrganizationsEmployee() as $organization) {
            $employee->removeOrganizationEmployee($organization);
        }

        $this->repository->save($employee);

        return new UserDto(
            id: $user->getId(),
            deleted: true
        );
    }

    public function serializerUser(User $user, array $ignoredAttribute = [], array $onlyAttribute = [], array $addAttribute = []): UserDto
    {
        $conditionOnlyAttribute = empty($onlyAttribute) ? [null] : [AbstractNormalizer::ATTRIBUTES => $onlyAttribute];
        $conditionIgnoredAttribute = empty($ignoredAttribute) ? [null] : [AbstractNormalizer::IGNORED_ATTRIBUTES => $ignoredAttribute];

        $condition = array_merge([
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true
        ], $conditionOnlyAttribute, $conditionIgnoredAttribute);
        $normalize = array_merge($this->serializer->normalize($user, null, $condition), $addAttribute);

        return $this->serializer->denormalize(
            $normalize, UserDto::class
        );
    }
}

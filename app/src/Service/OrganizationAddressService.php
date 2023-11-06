<?php

namespace App\Service;

use App\Dto\Organization\OrganizationAddress\OrganizationAddressDto;
use App\Dto\Organization\OrganizationDto;
use App\Entity\Organization\OrganizationAddress;
use App\Entity\User\User;
use App\Repository\CountryRepository;
use App\Repository\OrganizationAddressRepository;
use App\Repository\OrganizationRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Annotations\AnnotationReader;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrganizationAddressService
{
    private $serializer;

    public function __construct(
        private readonly OrganizationAddressRepository  $repository,
        private readonly CountryRepository              $countryRepository,
        private readonly OrganizationRepository         $organizationRepository,
        private readonly TranslatorInterface            $translator,
        private readonly TokenStorageInterface          $tokenStorageInterface,
        private readonly JWTTokenManagerInterface       $tokenManager,
        private readonly UserRepository                 $userRepository,
    )
    {
        $metadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new PropertyNormalizer($metadataFactory, null);

        $this->serializer = new Serializer([$normalizer, new DateTimeNormalizer(), new ObjectNormalizer()]);
    }

    public function create(OrganizationAddressDto $dto): OrganizationAddressDto
    {
        $address = new OrganizationAddress();
        $address->setCountry($this->countryRepository->findOneBy(['id' => $dto->country->id]));
        $address->setRegion($dto->region);
        $address->setCity($dto->city);
        $address->setStreet($dto->street);
        $address->setHouse($dto->house);
        $address->setApartment($dto->apartment);
        $address->setOrganization($this->organizationRepository->findOneBy(['id' => $dto->organization->id]));
        $address->setCreatedAt(new DateTimeImmutable());
        $this->repository->save($address);

        return $this->serialize($address, OrganizationAddressDto::class);
    }

    public function getCollection(?User $user, array $organizationAddresses = []): array
    {
        if (empty($user)) {
            throw new Exception($this->translator->trans('user_could_not_be_found'));
        }

        foreach($user->getOrganizations() as $organization) {
            if (!empty($organization->getOrganizationAddresses())) {
                foreach ($organization->getOrganizationAddresses() as $organizationAddress) {
                    $organizationAddresses[] = $this->serialize($organizationAddress, OrganizationAddressDto::class);;
                }
            }
        }

        return $organizationAddresses;
    }

    public function update(OrganizationAddressDto $dto): OrganizationAddressDto
    {
        $address = $this->repository->find($dto->id);

        if (empty($address)) {
            throw new Exception($this->translator->trans('address_could_not_be_found'));
        }

        $address->setCountry(empty($dto->region) ? $address->getRegion() : $this->countryRepository->findOneBy(['id' => $dto->country->id]));
        $address->setRegion($dto->region ?? $address->getRegion());
        $address->setCity($dto->city ?? $address->getCity());
        $address->setStreet($dto->street ?? $address->getStreet());
        $address->setHouse($dto->house ?? $address->getHouse());
        $address->setApartment($dto->apartment ?? $address->getApartment());
        $address->setDeleted($dto->deleted ?? $address->isDeleted());
        $address->setUpdatedAt(new DateTimeImmutable());

        $this->repository->save($address);

        return $this->serialize($address, OrganizationAddressDto::class);
    }

    public function delete($dto): OrganizationAddressDto
    {
        $address = $this->repository->find($dto->id);

        if (empty($address)) {
            throw new Exception($this->translator->trans('address_could_not_be_found'));
        }

        $address->setDeleted(1);
        $this->repository->save($address);

        return $this->serialize($address, OrganizationAddressDto::class);
    }

    public function serialize($entity, $dto, array $ignoredAttribute = [], array $onlyAttribute = [], array $addAttribute = [])
    {
        $conditionOnlyAttribute = empty($onlyAttribute) ? [null] : [AbstractNormalizer::ATTRIBUTES => $onlyAttribute];
        $conditionIgnoredAttribute = empty($ignoredAttribute) ? [null] : [AbstractNormalizer::IGNORED_ATTRIBUTES => $ignoredAttribute];

        $condition = array_merge([
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true
        ], $conditionOnlyAttribute, $conditionIgnoredAttribute);
        $normalize = array_merge($this->serializer->normalize($entity, null, $condition), $addAttribute);

        return $this->serializer->denormalize(
            $normalize, $dto
        );
    }
}

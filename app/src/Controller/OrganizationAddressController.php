<?php

namespace App\Controller;

use App\Action\Organization\OrganizationAddress\OrganizationAddressCreateAction;
use App\Action\Organization\OrganizationAddress\OrganizationAddressDeleteAction;
use App\Action\Organization\OrganizationAddress\OrganizationAddressGetCollectionAction;
use App\Action\Organization\OrganizationAddress\OrganizationAddressUpdateAction;
use App\Dto\Organization\OrganizationAddress\OrganizationAddressDto;
use App\Entity\User\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class OrganizationAddressController extends ApiController
{
    public function __construct(
        private readonly OrganizationAddressCreateAction        $createAction,
        private readonly OrganizationAddressGetCollectionAction $getCollectionAction,
        private readonly OrganizationAddressUpdateAction        $updateAction,
        private readonly OrganizationAddressDeleteAction        $deleteAction,
        private readonly ValidatorInterface                     $validatorInterface,
    )
    {
        parent::__construct($this->validatorInterface);
    }

    #[OA\RequestBody(
        content: new Model(type: OrganizationAddressDto::class, groups: ['create_address', 'organization_id'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Добавление адреса доставки организации',
        content: new Model(type: OrganizationAddressDto::class)
    )]
    #[OA\Tag(name: 'OrganizationAddress')]
    #[Route('api/organization-address/create', name: 'app_organization_address_create', methods: ['POST'])]
    public function create(#[MapRequestPayload] OrganizationAddressDto $dto): JsonResponse
    {
        try {
            $this->validation($dto, ['create_address']);

            $data = $this->createAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[OA\RequestBody(
        content: new Model(type: OrganizationAddressDto::class, groups: ['update_address'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Обновление адреса доставки организации',
        content: new Model(type: OrganizationAddressDto::class)
    )]
    #[OA\Tag(name: 'OrganizationAddress')]
    #[Route('api/organization-address/update', name: 'app_organization_address_update', methods: ['PUT'])]
    public function update(#[MapRequestPayload] OrganizationAddressDto $dto): JsonResponse
    {
        try {
            $this->validation($dto, ['update_address']);

            $data = $this->updateAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[OA\RequestBody(
        content: new Model(type: OrganizationAddressDto::class, groups: ['address_id'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Удаление адреса доставки организации',
        content: new Model(type: OrganizationAddressDto::class, groups: ['address_id'])
    )]
    #[OA\Tag(name: 'OrganizationAddress')]
    #[Route('api/organization-address/delete', name: 'app_organization_address_delete', methods: ['DELETE'])]
    public function delete(#[MapRequestPayload] OrganizationAddressDto $dto): JsonResponse
    {
        try {
            $data = $this->deleteAction->handle($dto);
            return $this->response($data->toArray(['address_id']));
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[OA\Response(
        response: 200,
        description: 'Получение списка адресов доставки организации',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: OrganizationAddressDto::class)))
    )]
    #[OA\Tag(name: 'OrganizationAddress')]
    #[Route('api/organization-address/get-collection', name: 'app_organization_address_get_collection', methods: ['GET'])]
    public function getCollection(#[CurrentUser] ?User $user): JsonResponse
    {
        try {
            $data = $this->getCollectionAction->handle($user);
            return $this->response($data);
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }
}

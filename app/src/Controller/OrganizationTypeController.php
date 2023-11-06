<?php

namespace App\Controller;


use App\Action\Organization\OrganizationType\OrganizationTypeCreateAction;
use App\Action\Organization\OrganizationType\OrganizationTypeDeleteAction;
use App\Action\Organization\OrganizationType\OrganizationTypeGetCollectionAction;
use App\Action\Organization\OrganizationType\OrganizationTypeUpdateAction;
use App\Dto\Auth\ReceiveCodeRequestDto;
use App\Dto\Auth\ReceiveCodeResponseDto;
use App\Dto\Organization\OrganizationType\OrganizationTypeDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use OpenApi\Attributes as OA;

class OrganizationTypeController extends ApiController
{
    public function __construct(
        private readonly OrganizationTypeCreateAction        $createAction,
        private readonly OrganizationTypeGetCollectionAction $getCollectionAction,
        private readonly OrganizationTypeUpdateAction        $updateAction,
        private readonly OrganizationTypeDeleteAction        $deleteAction,
    )
    {
    }

    #[OA\RequestBody(
        content: new Model(type: OrganizationTypeDto::class, groups: ['organization_type_name'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Создание типа организации',
        content: new Model(type: OrganizationTypeDto::class)
    )]
    #[OA\Tag(name: 'OrganizationType')]
    #[Route('api/organization-type/create', name: 'app_organization_type_create', methods: ['POST'])]
    public function create(#[MapRequestPayload] OrganizationTypeDto $dto): JsonResponse
    {
        try {
            $data = $this->createAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[OA\RequestBody(
        content: new Model(type: OrganizationTypeDto::class, groups: ['organization_type_id', 'organization_type_name'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Обновление типа организации',
        content: new Model(type: OrganizationTypeDto::class)
    )]
    #[OA\Tag(name: 'OrganizationType')]
    #[Route('api/organization-type/update', name: 'app_organization_type_update', methods: ['PUT'])]
    public function update(#[MapRequestPayload] OrganizationTypeDto $dto): JsonResponse
    {
        try {
            $data = $this->updateAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[OA\RequestBody(
        content: new Model(type: OrganizationTypeDto::class, groups: ['organization_type_id'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Удаление типа организации',
        content: new Model(type: OrganizationTypeDto::class, groups: ['organization_type_id'])
    )]
    #[OA\Tag(name: 'OrganizationType')]
    #[Route('api/organization-type/update', name: 'app_organization_type_update', methods: ['PUT'])]
    #[Route('api/organization-type/delete', name: 'app_organization_type_delete', methods: ['DELETE'])]
    public function delete(#[MapRequestPayload] OrganizationTypeDto $dto): JsonResponse
    {
        try {
            $data = $this->deleteAction->handle($dto);
            return $this->response($data->toArray(['organization_type_id']));
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }


    #[OA\Response(
        response: 200,
        description: 'Получения списка типов организации',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: OrganizationTypeDto::class)))
    )]
    #[OA\Tag(name: 'OrganizationType')]
    #[Route('api/organization-type/get-collection', name: 'app_organization_type_get_collection', methods: ['GET'])]
    public function getCollection(): JsonResponse
    {
        try {
            $data = $this->getCollectionAction->handle();
            return $this->response($data);
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }
}

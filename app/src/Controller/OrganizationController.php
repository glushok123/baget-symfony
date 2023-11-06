<?php

namespace App\Controller;

use App\Action\Organization\OrganizationCreateAction;
use App\Action\Organization\OrganizationDeleteAction;
use App\Action\Organization\OrganizationGetAction;
use App\Action\Organization\OrganizationGetCollectionAction;
use App\Action\Organization\OrganizationUpdateAction;
use App\Action\OrganizationDadataRequestAction;
use App\Dto\Organization\OrganizationDadataRequestDto;
use App\Dto\Organization\OrganizationDto;
use App\Entity\User\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

class OrganizationController extends ApiController
{
    public function __construct(
        private readonly OrganizationDadataRequestAction  $action,
        private readonly OrganizationCreateAction         $createAction,
        private readonly OrganizationUpdateAction         $updateAction,
        private readonly OrganizationGetCollectionAction  $getCollectionAction,
        private readonly OrganizationGetAction            $getUserAction,
        private readonly OrganizationDeleteAction         $deleteAction
    )
    {
    }

    #[IsGranted('ROLE_ORGANIZATION_EDIT')]
    #[OA\RequestBody(
        content: new Model(type: OrganizationDto::class, groups: ['create_organization', 'country_id', 'organization_type_id'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Создание организации',
        content: new Model(type: OrganizationDto::class)
    )]
    #[OA\Tag(name: 'Organization')]
    #[Route('api/organization/create', name: 'app_organization_create', methods: ['POST'])]
    public function create(#[MapRequestPayload] OrganizationDto $dto, #[CurrentUser] ?User $user): JsonResponse
    {
        try {
            $data = $this->createAction->handle($dto, $user);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_ORGANIZATION_EDIT')]
    #[OA\RequestBody(
        content: new Model(type: OrganizationDto::class, groups: ['organization_id', 'update_organization', 'organization_type_id'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Обновление организации (можно отправлять id и измененный атрибут)',
        content: new Model(type: OrganizationDto::class)
    )]
    #[OA\Tag(name: 'Organization')]
    #[Route('api/organization/update', name: 'app_organization_update', methods: ['PUT'])]
    public function update(#[MapRequestPayload] OrganizationDto $dto, #[CurrentUser] ?User $user): JsonResponse
    {
        try {
            $data = $this->updateAction->handle($dto, $user);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_ORGANIZATION_DELETE')]
    #[OA\RequestBody(
        content: new Model(type: OrganizationDto::class, groups: ['organization_id'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Удаление организации',
        content: new Model(type: OrganizationDto::class, groups: ['organization_id'])
    )]
    #[OA\Tag(name: 'Organization')]
    #[Route('api/organization/delete', name: 'app_organization_delete', methods: ['DELETE'])]
    public function delete(#[MapRequestPayload] OrganizationDto $dto, #[CurrentUser] ?User $user): JsonResponse
    {
        try {
            $data = $this->deleteAction->handle($dto, $user);
            return $this->response($data->toArray(['organization_id']));
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_ORGANIZATION_READ')]
    #[OA\Response(
        response: 200,
        description: 'Получение списка организаций',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: OrganizationDto::class)))
    )]
    #[OA\Tag(name: 'Organization')]
    #[Route('api/organization/get-collection', name: 'app_organization_get_collection', methods: ['GET'])]
    public function getCollection(): JsonResponse
    {
        try {
            $data = $this->getCollectionAction->handle();
            return $this->response($data);
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[IsGranted('ROLE_ORGANIZATION_READ')]
    #[OA\Parameter(
        parameter: "id",
        in:"query",
        name:"id",
        description:"id",
        example: 'id=10',
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Parameter(
        parameter: "inn",
        in:"query",
        name:"inn",
        description:"inn",
        example: 'inn=7721581040',
        schema: new OA\Schema(type: "string")
    )]
    #[OA\RequestBody(
        content: new Model(type: OrganizationDto::class, groups: ['organization_id', 'organization_inn'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Получение организации',
        content: new Model(type: OrganizationDto::class)
    )]
    #[OA\Tag(name: 'Organization')]
    #[Route('api/organization/get', name: 'app_organization_get', methods: ['GET'])]
    public function getUserByIdOrEmail(#[MapQueryString] OrganizationDto $dto): JsonResponse
    {
       try {
            $data = $this->getUserAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[Route('/api/organization/get-dadata', name: 'api_organization_dadata', methods: ['GET'])]
    public function getDadata(#[MapQueryString] OrganizationDadataRequestDto $dto): JsonResponse
    {
        try {
            $data = $this->action->handle($dto);

            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }
}

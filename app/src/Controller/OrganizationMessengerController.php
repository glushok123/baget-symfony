<?php

namespace App\Controller;


use App\Action\Organization\OrganizationMessenger\OrganizationMessengerCreateAction;
use App\Action\Organization\OrganizationMessenger\OrganizationMessengerDeleteAction;
use App\Action\Organization\OrganizationMessenger\OrganizationMessengerGetCollectionAction;
use App\Action\Organization\OrganizationMessenger\OrganizationMessengerUpdateAction;
use App\Dto\Organization\OrganizationMessenger\OrganizationMessengerDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use OpenApi\Attributes as OA;

class OrganizationMessengerController extends ApiController
{
    public function __construct(
        private readonly OrganizationMessengerCreateAction        $createAction,
        private readonly OrganizationMessengerGetCollectionAction $getCollectionAction,
        private readonly OrganizationMessengerUpdateAction        $updateAction,
        private readonly OrganizationMessengerDeleteAction        $deleteAction,
    )
    {
    }

    #[OA\RequestBody(
        content: new Model(type: OrganizationMessengerDto::class, groups: ['create_messenger', 'organization_id'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Создание мессенджера организации',
        content: new Model(type: OrganizationMessengerDto::class)
    )]
    #[OA\Tag(name: 'OrganizationMessenger')]
    #[Route('api/organization-messenger/create', name: 'app_organization_messenger_create', methods: ['POST'])]
    public function create(#[MapRequestPayload] OrganizationMessengerDto $dto): JsonResponse
    {
        try {
            $data = $this->createAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[OA\RequestBody(
        content: new Model(type: OrganizationMessengerDto::class, groups: ['create_messenger', 'organization_id', 'organization_messenger_id'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Обновление мессенджера организации',
        content: new Model(type: OrganizationMessengerDto::class)
    )]
    #[OA\Tag(name: 'OrganizationMessenger')]
    #[Route('api/organization-messenger/update', name: 'app_organization_messenger_update', methods: ['PUT'])]
    public function update(#[MapRequestPayload] OrganizationMessengerDto $dto): JsonResponse
    {
        try {
            $data = $this->updateAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[OA\RequestBody(
        content: new Model(type: OrganizationMessengerDto::class, groups: ['organization_messenger_id'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Удаление мессенджера организации',
        content: new Model(type: OrganizationMessengerDto::class, groups: ['organization_messenger_id'])
    )]
    #[OA\Tag(name: 'OrganizationMessenger')]
    #[Route('api/organization-messenger/delete', name: 'app_organization_messenger_delete', methods: ['DELETE'])]
    public function delete(#[MapRequestPayload] OrganizationMessengerDto $dto): JsonResponse
    {
        try {
            $data = $this->deleteAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }


    #[OA\Response(
        response: 200,
        description: 'Получение списка мессенджеров организации',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: OrganizationMessengerDto::class)))
    )]
    #[OA\Tag(name: 'OrganizationMessenger')]
    #[Route('api/organization-messenger/get-collection', name: 'app_organization_messenger_get_collection', methods: ['GET'])]
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

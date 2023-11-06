<?php

namespace App\Controller;

use App\Action\Messenger\MessengerCreateAction;
use App\Action\Messenger\MessengerDeleteAction;
use App\Action\Messenger\MessengerGetCollectionAction;
use App\Action\Messenger\MessengerUpdateAction;
use App\Dto\Messenger\MessengerDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use OpenApi\Attributes as OA;

class MessengerController extends ApiController
{
    public function __construct(
        private readonly MessengerCreateAction        $createAction,
        private readonly MessengerGetCollectionAction $getCollectionAction,
        private readonly MessengerUpdateAction        $updateAction,
        private readonly MessengerDeleteAction        $deleteAction
    )
    {
    }

    #[Route('api/messenger', name: 'app_messenger_create', methods: ['POST'])]
    public function create(#[MapRequestPayload] MessengerDto $dto): JsonResponse
    {
        try {
            $data = $this->createAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[Route('api/messenger/update', name: 'app_messenger_update', methods: ['PUT'])]
    public function update(#[MapRequestPayload] MessengerDto $dto): JsonResponse
    {
        try {
            $data = $this->updateAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[Route('api/messenger/delete', name: 'app_messenger_delete', methods: ['DELETE'])]
    public function delete(#[MapRequestPayload] MessengerDto $dto): JsonResponse
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
        description: 'Получение мессенджеров',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: MessengerDto::class)))
    )]
    #[OA\Tag(name: 'Messenger')]
    #[Route('api/messenger/get-collection', name: 'app_messenger_get_collection', methods: ['GET'])]
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

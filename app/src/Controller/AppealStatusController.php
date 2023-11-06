<?php

namespace App\Controller;

use App\Action\Appeal\AppealStatus\AppealStatusGetCollectionAction;
use App\Dto\Appeal\AppealStatus\AppealStatusDto;
use App\Dto\Product\ProductCollectionDto;
use App\Dto\User\UserDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class AppealStatusController extends ApiController
{
    public function __construct(
        private readonly AppealStatusGetCollectionAction       $getCollectionAction,
        private readonly ValidatorInterface                      $validatorInterface,
    )
    {
        parent::__construct($this->validatorInterface);
    }

    #[OA\Response(
        response: 200,
        description: 'Получение списка статусов обращений',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: AppealStatusDto::class)))
    )]
    #[OA\Tag(name: 'AppealStatus')]
    #[Route('api/appeal/status/get-collection', name: 'app_appeal_status_get_collection', methods: ['GET'])]
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

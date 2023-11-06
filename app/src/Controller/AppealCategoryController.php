<?php

namespace App\Controller;

use App\Action\Appeal\AppealCategory\AppealCategoryGetCollectionAction;
use App\Dto\Appeal\AppealCategory\AppealCategoryDto;
use App\Dto\Appeal\AppealStatus\AppealStatusDto;
use App\Dto\Product\ProductCollectionDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class AppealCategoryController extends ApiController
{
    public function __construct(
        private readonly AppealCategoryGetCollectionAction       $getCollectionAction,
        private readonly ValidatorInterface                      $validatorInterface,
    )
    {
        parent::__construct($this->validatorInterface);
    }

    #[OA\Response(
        response: 200,
        description: 'Получение списка категорий обращений',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: AppealCategoryDto::class)))
    )]
    #[OA\Tag(name: 'AppealCategory')]
    #[Route('api/appeal/category/get-collection', name: 'app_appeal_category_get_collection', methods: ['GET'])]
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

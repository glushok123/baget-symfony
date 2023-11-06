<?php

namespace App\Controller;


use App\Action\Country\CountryCreateAction;
use App\Action\Country\CountryGetCollectionAction;
use App\Action\Country\CountryUpdateAction;
use App\Dto\Country\CountryDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use OpenApi\Attributes as OA;

class CountryController extends ApiController
{
    public function __construct(
        private readonly CountryCreateAction        $createAction,
        private readonly CountryGetCollectionAction $getCollectionAction,
        private readonly CountryUpdateAction        $updateAction,
    )
    {
    }

    #[Route('api/country/create', name: 'app_country_create', methods: ['POST'])]
    public function create(#[MapRequestPayload] CountryDto $dto): JsonResponse
    {
        try {
            $data = $this->createAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

    #[Route('api/country/update', name: 'app_country_update', methods: ['PUT'])]
    public function update(#[MapRequestPayload] CountryDto $dto): JsonResponse
    {
        try {
            $data = $this->updateAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }

//    #[Route('api/country/delete', name: 'app_country_delete', methods: ['DELETE'])]
//    public function delete(#[MapRequestPayload] CountryDto $dto): JsonResponse
//    {
//        try {
//            $data = $this->deleteAction->handle($dto);
//            return $this->response($data->toArray());
//        } catch (Throwable $e) {
//            return $this->respondWithErrors($e->getMessage());
//        }
//    }

    #[OA\Response(
        response: 200,
        description: 'Получение стран',
        content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: CountryDto::class)))
    )]
    #[OA\Tag(name: 'Country')]
    #[Route('api/country/get-collection', name: 'app_country_get_collection', methods: ['GET'])]
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

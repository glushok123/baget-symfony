<?php

namespace App\Controller;

use App\Action\Auth\ProfileEditAction;
use App\Dto\Profile\RegisterFinalRequestDto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class ProfileController extends ApiController
{
    public function __construct(
        private readonly ProfileEditAction $profileEditAction
    )
    {
    }

    #[Route('api/profile/edit', name: 'api_profile', methods: ['PUT'])]
    public function edit(#[MapRequestPayload] RegisterFinalRequestDto $dto): Response
    {
        try {
            $data = $this->profileEditAction->handle($dto);
            return $this->response($data->toArray());
        } catch (Throwable $e) {
            return $this->respondWithErrors($e->getMessage());
        }
    }
}

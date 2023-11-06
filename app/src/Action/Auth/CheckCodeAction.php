<?php

namespace App\Action\Auth;

use App\Dto\Auth\CheckCodeRequestDto;
use App\Dto\Auth\CheckCodeResponseDto;
use App\Service\UserService;
use Symfony\Contracts\Translation\TranslatorInterface;

class CheckCodeAction
{
    public function __construct(
        private readonly UserService         $userService,
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function handle(CheckCodeRequestDto $dto): CheckCodeResponseDto
    {
        $message = $this->userService->checkCode($dto);

        return new CheckCodeResponseDto(
            message: $message
        );
    }
}
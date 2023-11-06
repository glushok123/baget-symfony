<?php

namespace App\Action\Auth;

use App\Dto\Auth\ReceiveCodeRequestDto;
use App\Dto\Auth\ReceiveCodeResponseDto;
use App\Service\UserService;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReceiveCodeAction
{
    public function __construct(
        private readonly UserService         $userService,
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function handle(ReceiveCodeRequestDto $dto): ReceiveCodeResponseDto
    {
        $codeReceived = $this->userService->generateCode($dto);

        return new ReceiveCodeResponseDto(
            message: $this->translator->trans('code_sent'),
            codeReceived: $codeReceived->getTimestamp()
        );
    }
}
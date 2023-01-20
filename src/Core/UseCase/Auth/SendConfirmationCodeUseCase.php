<?php

namespace App\Core\UseCase\Auth;

use App\Core\Service\Auth\Confirmation\SendConfirmationCodeService;

class SendConfirmationCodeUseCase
{
    public function __construct(
        private SendConfirmationCodeService $sendConfirmationCodeService
    ) {
    }

    public function send(string $phone)
    {
        $this->sendConfirmationCodeService->send($phone);
    }
}

<?php

namespace App\Auth\UseCase\UserConfirmation;

use App\Auth\Service\Confirmation\SendConfirmationCodeService;

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

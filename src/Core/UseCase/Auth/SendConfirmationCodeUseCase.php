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
        $code = rand(1111, 9999);
        $this->sendConfirmationCodeService->send($phone, $code);
    }
}

<?php

namespace App\Auth\UseCase\UserConfirmation;

use App\Auth\DTO\CodeConfirmationResult;
use App\Auth\Service\Confirmation\ConfirmCodeService;

class ConfirmCodeUseCase
{
    public function __construct(
        private ConfirmCodeService $confirmCodeService
    ) {
    }

    public function confirm(string $phone, string $code): CodeConfirmationResult
    {
        return $this->confirmCodeService->confirm($phone, $code);
    }
}

<?php

namespace App\Core\UseCase\Auth;

use App\Core\DTO\TokenPair;
use App\Core\Service\Auth\Confirmation\ConfirmCodeService;

class ConfirmCodeUseCase
{
    public function __construct(
        private ConfirmCodeService $confirmCodeService
    ) {
    }

    public function confirm(string $phone, string $code): TokenPair
    {
        return $this->confirmCodeService->confirm($phone, $code);
    }
}

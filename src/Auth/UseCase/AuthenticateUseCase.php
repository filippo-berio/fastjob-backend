<?php

namespace App\Auth\UseCase;

use App\Auth\Entity\User;
use App\Auth\Service\AuthenticateService;

class AuthenticateUseCase
{
    public function __construct(
        private AuthenticateService $authenticateService,
    ) {
    }

    public function authenticate(string $accessToken, ?string $refreshToken): User
    {
        return $this->authenticateService->authenticate($accessToken, $refreshToken);
    }
}

<?php

namespace App\Auth\Service\User;

use App\Auth\DTO\TokenPair;
use App\Auth\Entity\User;
use App\Auth\Service\Token\CreateAccessTokenService;
use App\Auth\Service\Token\GetRefreshTokenService;

class LoginUserService
{
    public function __construct(
        private CreateAccessTokenService $accessTokenService,
        private GetRefreshTokenService $refreshTokenService,
    ) {
    }

    public function login(User $user): TokenPair
    {
        $accessToken = $this->accessTokenService->create($user);
        $refreshToken = $this->refreshTokenService->getOrCreate($user);
        return new TokenPair($accessToken, $refreshToken->getToken());
    }
}

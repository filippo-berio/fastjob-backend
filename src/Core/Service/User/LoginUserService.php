<?php

namespace App\Core\Service\User;

use App\Core\DTO\TokenPair;
use App\Core\Entity\User;
use App\Core\Service\Auth\Token\CreateAccessTokenService;
use App\Core\Service\Auth\Token\GetRefreshTokenService;

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

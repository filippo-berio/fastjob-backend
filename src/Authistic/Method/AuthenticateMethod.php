<?php

namespace App\Authistic\Method;

use App\Http\Method\Method;

class AuthenticateMethod extends Method
{
    public function __construct(
        private string $accessToken,
        private string $refreshToken
    ) {
    }

    public function getHttpMethod(): string
    {
        return 'POST';
    }

    public function getUri(): string
    {
        return '/authenticate';
    }

    public function buildJson(): ?array
    {
        return [
            'accessToken' => $this->accessToken,
            'refreshToken' => $this->refreshToken,
        ];
    }
}

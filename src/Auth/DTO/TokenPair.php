<?php

namespace App\Auth\DTO;

readonly class TokenPair
{
    public function __construct(
        public string $accessToken,
        public string $refreshToken
    ) {
    }
}

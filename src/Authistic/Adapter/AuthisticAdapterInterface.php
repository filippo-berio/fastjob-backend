<?php

namespace App\Authistic\Adapter;

use App\Authistic\DTO\TokenPair;

interface AuthisticAdapterInterface
{
    public function login(string $login, string $password): TokenPair;

    public function authenticate(string $accessToken, string $refreshToken): TokenPair;

    public function register(string $login, string $password);
}

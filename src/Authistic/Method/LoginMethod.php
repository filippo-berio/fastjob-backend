<?php

namespace App\Authistic\Method;

use App\Http\Method\Method;

class LoginMethod extends Method
{
    public function __construct(
        private string $login,
        private string $password,
    ) {
    }

    public function getHttpMethod(): string
    {
        return 'POST';
    }

    public function getUri(): string
    {
        return '/login';
    }

    public function buildJson(): ?array
    {
        return [
            'login' => $this->login,
            'password' => $this->password,
        ];
    }
}

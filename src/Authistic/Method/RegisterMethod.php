<?php

namespace App\Authistic\Method;

use App\Http\Method\Method;

class RegisterMethod extends Method
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
        return '/register';
    }

    public function buildJson(): ?array
    {
        return [
            'login' => $this->login,
            'password' => $this->password,
        ];
    }
}

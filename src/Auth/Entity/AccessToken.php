<?php

namespace App\Auth\Entity;

class AccessToken
{
    public function __construct(
        private User $user,
        private string $value,
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

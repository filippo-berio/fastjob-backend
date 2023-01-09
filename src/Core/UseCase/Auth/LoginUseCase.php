<?php

namespace App\Core\UseCase\Auth;

use App\Authistic\Adapter\AuthisticAdapter;
use App\Authistic\DTO\TokenPair;

class LoginUseCase
{
    public function __construct(
        private AuthisticAdapter $authisticAdapter
    ) {
    }

    public function __invoke(string $login, string $password): TokenPair
    {
        return $this->authisticAdapter->login($login, $password);
    }
}

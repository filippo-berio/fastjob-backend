<?php

namespace App\Auth\Service\User;

use App\Auth\Command\User\SaveUser;
use App\Auth\Entity\User;
use App\CQRS\Bus\CommandBusInterface;

class RegisterUserService
{
    public function __construct(
        private CommandBusInterface $commandBus
    ) {
    }

    public function register(string $phone): User
    {
        $user = new User($phone);
        return $this->commandBus->handle(new SaveUser($user));
    }
}
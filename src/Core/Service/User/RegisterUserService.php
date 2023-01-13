<?php

namespace App\Core\Service\User;

use App\Core\Data\Command\User\SaveUser;
use App\Core\Entity\User;
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

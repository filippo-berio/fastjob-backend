<?php

namespace App\Core\Infrastructure\Gateway;

use App\Auth\Entity\User as ExternalUser;
use App\Auth\UseCase\User\GetUserByIdUseCase;

class UserGateway
{
    public function __construct(
        private GetUserByIdUseCase $getUserByIdUseCase
    ) {
    }

    public function findUser(int $id): ExternalUser
    {
        return $this->getUserByIdUseCase->get($id);
    }
}

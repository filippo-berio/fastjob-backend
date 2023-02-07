<?php

namespace App\Api\Gateway;

use App\Auth\Entity\User as AuthUser;
use App\Core\Application\UseCase\Profile\FindProfileByUserUseCase;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\User;

class CoreGateway
{
    public function __construct(
        private FindProfileByUserUseCase $findProfileByUser
    ) {
    }

    public function findProfileByAuthUser(AuthUser $user): ?Profile
    {
        return $this->findProfileByUser->find(new User($user->getId(), $user->getPhone()));
    }
}

<?php

namespace App\Core\UseCase\Profile;

use App\Auth\Entity\User;
use App\Core\Entity\Profile;
use App\Core\Exception\Profile\ProfileNotFoundException;
use App\Core\Query\Profile\FindByUser\FindProfileByUser;
use App\CQRS\Bus\QueryBusInterface;

class GetProfileUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus
    ) {
    }

    public function get(User $user): Profile
    {
        if (!$profile = $this->queryBus->handle(new FindProfileByUser($user))) {
            throw new ProfileNotFoundException();
        }
        return $profile;
    }
}

<?php

namespace App\Core\Application\UseCase\Profile;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\User;
use App\Core\Domain\Query\Profile\FindProfileByUser;
use App\CQRS\Bus\QueryBusInterface;

class FindProfileByUserUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function find(User $user): ?Profile
    {
        return $this->queryBus->query(new FindProfileByUser($user));
    }
}

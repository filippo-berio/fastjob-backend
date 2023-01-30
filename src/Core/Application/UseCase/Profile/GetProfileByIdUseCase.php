<?php

namespace App\Core\Application\UseCase\Profile;

use App\Core\Domain\Query\Profile\FindProfileById;
use App\CQRS\Bus\QueryBusInterface;
use App\Report\Domain\Entity\Profile;

class GetProfileByIdUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function get(int $id): ?Profile
    {
        return $this->queryBus->query(new FindProfileById($id));
    }
}

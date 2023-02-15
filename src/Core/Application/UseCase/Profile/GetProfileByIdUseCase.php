<?php

namespace App\Core\Application\UseCase\Profile;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Query\Profile\FindProfileById;
use App\Lib\CQRS\Bus\QueryBusInterface;

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

<?php

namespace App\Auth\UseCase\User;

use App\Auth\Entity\User;
use App\Auth\Query\User\FindById\FindUserById;
use App\Lib\CQRS\Bus\QueryBusInterface;

class GetUserByIdUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function get(int $id): User
    {
        return $this->queryBus->query(new FindUserById($id));
    }
}

<?php

namespace App\Core\Application\UseCase\Task;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Query\Task\FindTaskByAuthor;
use App\Lib\CQRS\Bus\QueryBusInterface;

class GetProfileTasksUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    /**
     * @param Profile $profile
     * @return Task[]
     */
    public function get(Profile $profile): array
    {
        return $this->queryBus->query(new FindTaskByAuthor($profile));
    }
}

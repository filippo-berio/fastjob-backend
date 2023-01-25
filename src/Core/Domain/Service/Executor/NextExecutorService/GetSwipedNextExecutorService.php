<?php

namespace App\Core\Domain\Service\Executor\NextExecutorService;

use App\Core\Domain\Entity\NextExecutor;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Exception\Task\TaskNotFoundException;
use App\Core\Domain\Query\Task\FindByProfile\FindTaskByAuthor;
use App\Core\Domain\Repository\NextExecutorRepositoryInterface;
use App\CQRS\Bus\QueryBusInterface;

class GetSwipedNextExecutorService implements NextExecutorServiceInterface
{
    public function __construct(
        private NextExecutorRepositoryInterface $nextExecutorRepository,
        private QueryBusInterface               $queryBus,
    ) {
    }

    public function get(Profile $author): ?NextExecutor
    {
        $tasks = $this->queryBus->query(new FindTaskByAuthor($author));
        if (empty($tasks)) {
            throw new TaskNotFoundException();
        }

        return $this->nextExecutorRepository->nextSwipedExecutor($author);
    }
}

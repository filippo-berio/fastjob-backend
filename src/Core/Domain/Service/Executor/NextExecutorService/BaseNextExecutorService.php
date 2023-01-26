<?php

namespace App\Core\Domain\Service\Executor\NextExecutorService;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Exception\Task\TaskNotFoundException;
use App\Core\Domain\Query\Task\FindWaitTaskByAuthor;
use App\Core\Domain\Repository\NextExecutorRepositoryInterface;
use App\CQRS\Bus\QueryBusInterface;

abstract class BaseNextExecutorService implements NextExecutorServiceInterface
{
    public function __construct(
        protected NextExecutorRepositoryInterface $nextExecutorRepository,
        protected QueryBusInterface $queryBus,
    ) {
    }

    protected function checkTasks(Profile $author)
    {
        $tasks = $this->queryBus->query(new FindWaitTaskByAuthor($author));
        if (empty($tasks)) {
            throw new TaskNotFoundException();
        }
    }
}

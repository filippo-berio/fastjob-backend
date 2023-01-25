<?php

namespace App\Core\Service\Executor\NextExecutorService;

use App\Core\Entity\NextExecutor;
use App\Core\Entity\Profile;
use App\Core\Exception\Task\TaskNotFoundException;
use App\Core\Query\Task\FindByProfile\FindTaskByAuthor;
use App\Core\Repository\NextExecutorRepository;
use App\CQRS\Bus\QueryBusInterface;

class GetSwipedNextExecutorService implements NextExecutorServiceInterface
{
    public function __construct(
        private NextExecutorRepository $nextExecutorRepository,
        private QueryBusInterface $queryBus,
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

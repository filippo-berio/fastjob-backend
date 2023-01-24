<?php

namespace App\Core\UseCase\Task;

use App\Core\Entity\Profile;
use App\Core\Exception\Task\TaskNotFoundException;
use App\Core\Query\Task\FindById\FindTaskById;
use App\Core\Service\Task\GetNextSwipedExecutorService;
use App\CQRS\Bus\QueryBusInterface;

class NextSwipedExecutorUseCase
{
    public function __construct(
        private GetNextSwipedExecutorService $getNextSwipedExecutorService,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function get(Profile $author, int $taskId): ?Profile
    {
        $task = $this->queryBus->query(new FindTaskById($taskId));
        if (!$task) {
            throw new TaskNotFoundException();
        }
        return $this->getNextSwipedExecutorService->get($author, $task);
    }
}

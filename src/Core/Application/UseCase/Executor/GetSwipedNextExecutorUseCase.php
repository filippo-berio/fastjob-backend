<?php

namespace App\Core\Application\UseCase\Executor;

use App\Core\Application\Exception\Task\TaskNotFoundException;
use App\Core\Domain\Entity\NextExecutor;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Query\Task\FindTaskByAuthorAndId;
use App\Core\Domain\Service\Executor\GetSwipedNextExecutorService;
use App\Lib\CQRS\Bus\QueryBusInterface;

class GetSwipedNextExecutorUseCase
{
    public function __construct(
        private GetSwipedNextExecutorService $getSwipedNextExecutorService,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function get(Profile $profile, int $taskId): ?NextExecutor
    {
        $task = $this->queryBus->query(new FindTaskByAuthorAndId($profile, $taskId));
        if (!$task) {
            throw new TaskNotFoundException();
        }
        return $this->getSwipedNextExecutorService->get($task);
    }
}

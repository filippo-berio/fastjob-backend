<?php

namespace App\Core\Application\UseCase\Task;

use App\Core\Application\Exception\Task\TaskNotFoundException;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Query\Task\FindTaskById;
use App\Core\Domain\Service\Task\CancelTaskExecutionService;
use App\CQRS\Bus\QueryBusInterface;

class CancelTaskExecutionUseCase
{
    public function __construct(
        private CancelTaskExecutionService $cancelTaskExecutionService,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function cancel(Profile $profile, int $taskId)
    {
        /** @var Task $task */
        $task = $this->queryBus->query(new FindTaskById($taskId));
        if (!$task) {
            throw new TaskNotFoundException();
        }

        $this->cancelTaskExecutionService->cancel($profile, $task);
    }
}

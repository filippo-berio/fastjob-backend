<?php

namespace App\Core\Application\UseCase\Task;

use App\Core\Application\Exception\Task\TaskNotFoundException;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Query\Task\FindTaskByAuthorAndId;
use App\Core\Domain\Service\Task\FinishTaskService;
use App\CQRS\Bus\QueryBusInterface;

class FinishTaskUseCase
{
    public function __construct(
        private FinishTaskService $finishTaskService,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function finish(
        Profile $profile,
        int $taskId,
        int $rating,
        ?string $reviewComment = null,
    ) {
        $task = $this->queryBus->query(new FindTaskByAuthorAndId($profile, $taskId));
        if (!$task) {
            throw new TaskNotFoundException();
        }

        $this->finishTaskService->finish($task, $rating, $reviewComment);
    }
}

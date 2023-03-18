<?php

namespace App\Core\Domain\Service\Executor;

use App\Core\Domain\Entity\NextExecutor;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Entity\TaskSwipe;
use App\Core\Domain\Exception\Task\TaskUnavailableToSwipe;
use App\Core\Domain\Repository\NextExecutorRepositoryInterface;

class GetSwipedNextExecutorService
{
    public function __construct(
        private NextExecutorRepositoryInterface $nextExecutorRepository,
    ) {
    }

    /**
     * @param Task $task
     * @param int $limit
     * @return TaskSwipe[]
     */
    public function get(Task $task, int $limit): array
    {
        if (in_array($task->getStatus(), Task::STATUSES_NO_SWIPES)) {
            throw new TaskUnavailableToSwipe();
        }
        return $this->nextExecutorRepository->nextSwipedExecutor($task, $limit);
    }
}

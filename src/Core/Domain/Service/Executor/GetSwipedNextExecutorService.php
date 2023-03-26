<?php

namespace App\Core\Domain\Service\Executor;

use App\Core\Domain\Entity\NextExecutor;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Entity\TaskSwipe;
use App\Core\Domain\Exception\Task\TaskUnavailableToSwipe;
use App\Core\Domain\Query\Task\FindTaskByAuthor;
use App\Core\Domain\Repository\NextExecutorRepositoryInterface;
use App\Lib\CQRS\Bus\QueryBusInterface;

class GetSwipedNextExecutorService
{
    public function __construct(
        private NextExecutorRepositoryInterface $nextExecutorRepository,
        private QueryBusInterface $queryBus,
    ) {
    }

    /**
     * @param Profile $profile
     * @param int $limit
     * @param Task|null $task
     * @return TaskSwipe[]
     */
    public function get(Profile $profile, int $limit, ?Task $task = null): array
    {
        if ($task && in_array($task->getStatus(), Task::STATUSES_NO_SWIPES)) {
            throw new TaskUnavailableToSwipe();
        }
        if (!$task) {
            $tasks = $this->queryBus->query(new FindTaskByAuthor($profile));
            if (empty($tasks)) {
                return [];
            }
            $task = $tasks[0];
        }
        return $this->nextExecutorRepository->nextSwipedExecutor($task, $limit);
    }
}

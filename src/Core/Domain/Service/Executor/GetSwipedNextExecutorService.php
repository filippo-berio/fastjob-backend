<?php

namespace App\Core\Domain\Service\Executor;

use App\Core\Domain\Entity\NextExecutor;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Exception\BaseException;
use App\Core\Domain\Exception\Task\TaskUnavailableToSwipe;
use App\Core\Domain\Repository\NextExecutorRepositoryInterface;

class GetSwipedNextExecutorService
{
    const TYPE = 'swiped';

    public function __construct(
        private NextExecutorRepositoryInterface $nextExecutorRepository,
    )
    {
    }


    public function get(Task $task): ?NextExecutor
    {
        if (in_array($task->getStatus(), Task::STATUSES_NO_SWIPES)) {
            throw new TaskUnavailableToSwipe();
        }
        return $this->nextExecutorRepository->nextSwipedExecutor($task);
    }
}

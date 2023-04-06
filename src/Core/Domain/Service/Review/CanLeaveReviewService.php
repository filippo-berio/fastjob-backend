<?php

namespace App\Core\Domain\Service\Review;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Query\Task\FindAvailableReviewTasksForExecutor;
use App\Core\Domain\Query\Task\FindFinishedTaskByExecutor;
use App\Core\Domain\Repository\ReviewRepositoryInterface;
use App\Lib\CQRS\Bus\QueryBusInterface;

class CanLeaveReviewService
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private ReviewRepositoryInterface $reviewRepository,
    ) {
    }

    public function can(Profile $profile, Task $task): bool
    {
        /** @var Task[] $tasks */
        $tasks = $this->queryBus->query(new FindFinishedTaskByExecutor($profile));
        foreach ($tasks as $finishedTask) {
            if ($finishedTask->getId() === $task->getId()) {
                return !$this->reviewRepository->findForTaskAndExecutor($task, $profile);
            }
        }
        return false;
    }
}

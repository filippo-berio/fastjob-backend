<?php

namespace App\Core\Domain\Service\Review;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Query\Task\FindAvailableReviewTasksForExecutor;
use App\Lib\CQRS\Bus\QueryBusInterface;

class CanLeaveReviewService
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function can(Profile $profile, Task $task): bool
    {
        /** @var Task[] $available */
        $available = $this->queryBus->query(new FindAvailableReviewTasksForExecutor($profile));
        foreach ($available as $availableTask) {
            if ($availableTask->getId() === $task->getId()) {
                return true;
            }
        }
        return false;
    }
}

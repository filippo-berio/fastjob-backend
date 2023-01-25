<?php

namespace App\Core\Domain\Service\TaskSwipe;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Entity\TaskSwipe;
use App\Core\Domain\Exception\Task\TaskUnavailableToSwipe;
use App\Core\Domain\Exception\TaskSwipe\CantSwipeOwnTask;
use App\Core\Domain\Exception\TaskSwipe\TaskSwipeExistsException;
use App\Core\Domain\Repository\PendingTaskRepositoryInterface;
use App\Core\Domain\Repository\TaskSwipeRepositoryInterface;

class CreateTaskSwipeService
{
    public function __construct(
        private TaskSwipeRepositoryInterface   $taskSwipeRepository,
        private PendingTaskRepositoryInterface $pendingTaskRepository,
    ) {
    }

    public function create(
        Profile $profile,
        Task $task,
        string $type,
        ?int $customPrice = null
    ): TaskSwipe {
        $this->checkExisting($profile, $task);

        if ($task->getAuthor()->getId() === $profile->getId()) {
            throw new CantSwipeOwnTask();
        }

        if (!$task->isAvailableToSwipe()) {
            throw new TaskUnavailableToSwipe();
        }

        $this->pendingTaskRepository->clear($profile);
        $taskSwipe = new TaskSwipe($task, $profile, $type, $customPrice);
        return $this->taskSwipeRepository->save($taskSwipe);
    }

    private function checkExisting(Profile $profile, Task $task)
    {
        $taskSwipe = $this->taskSwipeRepository->findByProfileAndTask($profile, $task);
        if ($taskSwipe) {
            throw new TaskSwipeExistsException();
        }
    }
}

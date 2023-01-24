<?php

namespace App\Core\Service\TaskSwipe;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Entity\TaskSwipe;
use App\Core\Exception\TaskSwipe\TaskSwipeExistsException;
use App\Core\Repository\PendingTaskRepository;
use App\Core\Repository\TaskSwipeRepository;

class CreateTaskSwipeService
{
    public function __construct(
        private TaskSwipeRepository $taskSwipeRepository,
        private PendingTaskRepository $pendingTaskRepository,
    ) {
    }

    public function create(
        Profile $profile,
        Task $task,
        string $type,
        ?int $customPrice = null
    ): TaskSwipe {
        $this->checkExisting($profile, $task);
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

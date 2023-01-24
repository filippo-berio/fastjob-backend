<?php

namespace App\Core\Service\ExecutorSwipe;

use App\Core\Entity\ExecutorSwipe;
use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Exception\ExecutorSwipe\ExecutorSwipeExistsException;
use App\Core\Exception\ExecutorSwipe\ExecutorSwipeSelfAssignException;
use App\Core\Exception\Task\TaskNotFoundException;
use App\Core\Repository\ExecutorSwipeRepository;

class CreateExecutorSwipeService
{
    public function __construct(
        private ExecutorSwipeRepository $executorSwipeRepository,
    ) {
    }

    public function create(
        Profile $user,
        Task $task,
        Profile $executor,
        string $type,
    ): ExecutorSwipe {
        if ($task->getAuthor()->getId() !== $user->getId()) {
            throw new TaskNotFoundException();
        }

        if ($user->getId() === $executor->getId()) {
            throw new ExecutorSwipeSelfAssignException();
        }

        $existing = $this->executorSwipeRepository->findByAuthorAndTask($executor, $task);
        if ($existing) {
            throw new ExecutorSwipeExistsException();
        }

        $executorSwipe = new ExecutorSwipe($task, $executor, $type);
        return $this->executorSwipeRepository->save($executorSwipe);
    }
}

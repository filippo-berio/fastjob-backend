<?php

namespace App\Core\Domain\Service\ExecutorSwipe;

use App\Core\Domain\Entity\ExecutorSwipe;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Exception\ExecutorSwipe\ExecutorSwipeExistsException;
use App\Core\Domain\Exception\ExecutorSwipe\ExecutorSwipeSelfAssignException;
use App\Core\Domain\Exception\Task\TaskNotFoundException;
use App\Core\Domain\Repository\ExecutorSwipeRepositoryInterface;

class CreateExecutorSwipeService
{
    public function __construct(
        private ExecutorSwipeRepositoryInterface $executorSwipeRepository,
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

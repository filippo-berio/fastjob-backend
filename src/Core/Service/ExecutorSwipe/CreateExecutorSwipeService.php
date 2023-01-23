<?php

namespace App\Core\Service\ExecutorSwipe;

use App\Core\Command\ExecutorSwipe\Create\CreateExecutorSwipe;
use App\Core\Entity\ExecutorSwipe;
use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Exception\ExecutorSwipe\ExecutorSwipeExistsException;
use App\Core\Exception\ExecutorSwipe\ExecutorSwipeSelfAssignException;
use App\Core\Exception\Task\TaskNotFoundException;
use App\Core\Query\ExecutorSwipe\FindByProfileTask\FindExecutorSwipeByProfileTask;
use App\CQRS\Bus\CommandBusInterface;
use App\CQRS\Bus\QueryBusInterface;

class CreateExecutorSwipeService
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function create(
        Profile $user,
        Task $task,
        Profile $executor,
        string $type,
    ): ExecutorSwipe {
        if ($task->getEmployer()->getId() !== $user->getId()) {
            throw new TaskNotFoundException();
        }

        if ($user->getId() === $executor->getId()) {
            throw new ExecutorSwipeSelfAssignException();
        }

        $existing = $this->queryBus->query(new FindExecutorSwipeByProfileTask($executor, $task));
        if ($existing) {
            throw new ExecutorSwipeExistsException();
        }

        $executorSwipe = new ExecutorSwipe($task, $executor, $type);
        return $this->commandBus->execute(new CreateExecutorSwipe($executorSwipe));
    }
}

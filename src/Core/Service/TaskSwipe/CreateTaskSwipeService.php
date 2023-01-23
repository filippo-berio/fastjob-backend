<?php

namespace App\Core\Service\TaskSwipe;

use App\Core\Command\TaskSwipe\Create\CreateTaskSwipe;
use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Entity\TaskSwipe;
use App\Core\Exception\TaskSwipe\TaskSwipeExistsException;
use App\Core\Query\TaskSwipe\FindByProfileTask\FindTaskSwipeByProfileAndTask;
use App\CQRS\Bus\CommandBusInterface;
use App\CQRS\Bus\QueryBusInterface;

class CreateTaskSwipeService
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function create(
        Profile $profile,
        Task $task,
        string $type,
        ?int $customPrice = null
    ): TaskSwipe {
        $this->checkExisting($profile, $task);
        $taskSwipe = new TaskSwipe($task, $profile, $type, $customPrice);
        return $this->commandBus->execute(new CreateTaskSwipe($taskSwipe));
    }

    private function checkExisting(Profile $profile, Task $task)
    {
        $taskSwipe = $this->queryBus->query(new FindTaskSwipeByProfileAndTask($profile, $task));
        if ($taskSwipe) {
            throw new TaskSwipeExistsException();
        }
    }
}

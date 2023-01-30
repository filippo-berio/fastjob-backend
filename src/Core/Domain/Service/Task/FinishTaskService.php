<?php

namespace App\Core\Domain\Service\Task;

use App\Core\Domain\Command\Task\Save\SaveTask;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Exception\Task\TaskNotInWorkException;
use App\CQRS\Bus\CommandBusInterface;

class FinishTaskService
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function finish(Task $task)
    {
        if (!$task->isInWork()) {
            throw new TaskNotInWorkException();
        }
        $task->finish();
        $this->commandBus->execute(new SaveTask($task));
    }
}

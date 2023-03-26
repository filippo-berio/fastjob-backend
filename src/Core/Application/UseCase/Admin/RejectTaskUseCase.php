<?php

namespace App\Core\Application\UseCase\Admin;

use App\Core\Domain\Command\Task\Save\SaveTask;
use App\Core\Domain\Query\Task\FindTaskById;
use App\Lib\CQRS\Bus\CommandBusInterface;
use App\Lib\CQRS\Bus\QueryBusInterface;

class RejectTaskUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function reject(int $taskId)
    {
        $task = $this->queryBus->query(new FindTaskById($taskId));
        $task->reject();
        $this->commandBus->execute(new SaveTask($task));
    }
}

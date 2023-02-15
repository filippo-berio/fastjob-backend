<?php

namespace App\Core\Domain\Service\Task;

use App\Core\Domain\Command\Task\Save\SaveTask;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Lib\CQRS\Bus\CommandBusInterface;

class OfferTaskService
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function offer(Task $task, Profile $executor)
    {
        $task->offer($executor);
        $this->commandBus->execute(new SaveTask($task));
    }
}

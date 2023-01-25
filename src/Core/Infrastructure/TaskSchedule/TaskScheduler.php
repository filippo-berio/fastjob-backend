<?php

namespace App\Core\Infrastructure\TaskSchedule;

use App\Core\Domain\TaskSchedule\TaskInterface;
use App\Core\Domain\TaskSchedule\TaskSchedulerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class TaskScheduler implements TaskSchedulerInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function dispatch(TaskInterface $task)
    {
        $this->messageBus->dispatch($task);
    }
}

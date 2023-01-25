<?php

namespace App\Core\Domain\Entity;

class NextExecutor
{
    public function __construct(
        private Task $task,
        private Profile $executor,
        private ?TaskSwipe $taskSwipe = null,
    ) {
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getExecutor(): Profile
    {
        return $this->executor;
    }

    public function getTaskSwipe(): ?TaskSwipe
    {
        return $this->taskSwipe;
    }
}

<?php

namespace App\Core\Domain\Entity;

class ExecutionCancel
{
    public function __construct(
        protected Profile $executor,
        protected Task $task,
        protected bool $forced = false, // отмена автором задачи
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

    public function isForced(): bool
    {
        return $this->forced;
    }
}

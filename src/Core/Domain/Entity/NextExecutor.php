<?php

namespace App\Core\Domain\Entity;

use JsonSerializable;

class NextExecutor implements JsonSerializable
{
    public function __construct(
        private Task       $task,
        private Profile    $executor,
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

    public function jsonSerialize(): array
    {
        return [
            'task' => $this->task,
            'profile' => $this->executor,
        ];
    }
}

<?php

namespace App\Core\Domain\Entity;

class SwipeMatch
{
    public function __construct(
        protected Task    $task,
        protected Profile $executor,
        protected ?int    $customPrice = null,
    ) {
    }

    public function getExecutor(): Profile
    {
        return $this->executor;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getCustomPrice(): ?int
    {
        return $this->customPrice;
    }
}

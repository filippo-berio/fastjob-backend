<?php

namespace App\Core\Domain\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

class SwipeMatch
{
    public function __construct(
        #[Groups(['match'])]
        protected Task    $task,
        #[Groups(['match'])]
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

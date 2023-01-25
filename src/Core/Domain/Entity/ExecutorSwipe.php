<?php

namespace App\Core\Domain\Entity;

class ExecutorSwipe extends Swipe
{
    protected ?int $id = null;
    protected Task $task;
    protected Profile $profile;
    protected string $type;

    public function getId(): ?int
    {
        return $this->id;
    }
}

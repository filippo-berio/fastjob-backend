<?php

namespace App\Core\Domain\DTO\Task;

use App\Core\Domain\Entity\Task;
use JsonSerializable;

class ExecutorTaskList implements JsonSerializable
{
    /**
     * @param Task[] $work
     * @param Task[] $offers
     * @param Task[] $matches
     * @param Task[] $swipes
     */
    public function __construct(
        public array $work,
        public array $offers,
        public array $matches,
        public array $swipes,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'work' => $this->work,
            'offers' => $this->offers,
            'matches' => $this->matches,
            'swipes' => $this->swipes,
        ];
    }
}

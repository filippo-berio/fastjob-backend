<?php

namespace App\Core\Application\DTO;

use App\Core\Domain\Entity\SwipeMatch;
use App\Core\Domain\Entity\Task;

readonly class TaskMatches
{
    /**
     * @param Task $task
     * @param SwipeMatch[] $matches
     */
    public function __construct(
        public Task $task,
        public array $matches
    ) {
    }
}

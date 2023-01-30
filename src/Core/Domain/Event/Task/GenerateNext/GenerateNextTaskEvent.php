<?php

namespace App\Core\Domain\Event\Task\GenerateNext;

use App\Core\Domain\Event\EventInterface;

readonly class GenerateNextTaskEvent implements EventInterface
{
    public function __construct(
        public int $profileId,
        public int $count,
    ) {
    }
}

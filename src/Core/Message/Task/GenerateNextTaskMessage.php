<?php

namespace App\Core\Message\Task;

readonly class GenerateNextTaskMessage
{
    public function __construct(
        public int $profileId,
        public int $count
    ) {
    }
}

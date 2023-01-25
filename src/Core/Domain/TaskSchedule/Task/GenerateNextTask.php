<?php

namespace App\Core\Domain\TaskSchedule\Task;

use App\Core\Domain\TaskSchedule\TaskInterface;

readonly class GenerateNextTask implements TaskInterface
{
    public function __construct(
        public int $profileId,
        public int $count
    ) {
    }
}

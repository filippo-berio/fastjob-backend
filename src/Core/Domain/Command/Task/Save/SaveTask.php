<?php

namespace App\Core\Domain\Command\Task\Save;

use App\Core\Domain\Entity\Task;
use App\CQRS\CommandInterface;

class SaveTask implements CommandInterface
{
    public function __construct(
        public Task $task,
    ) {
    }
}

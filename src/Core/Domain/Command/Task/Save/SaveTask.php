<?php

namespace App\Core\Domain\Command\Task\Save;

use App\Core\Domain\Entity\Task;
use App\CQRS\BaseCommand;

class SaveTask extends BaseCommand
{
    public function __construct(
        public Task $task,
    ) {
    }
}

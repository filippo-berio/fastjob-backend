<?php

namespace App\Core\Command\Task\Save;

use App\Core\Entity\Task;
use App\CQRS\BaseCommand;

class SaveTask extends BaseCommand
{
    public function __construct(
        public Task $task,
    ) {
    }
}

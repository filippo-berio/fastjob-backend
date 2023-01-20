<?php

namespace App\Core\Command\TaskSwipe\Create;

use App\Core\Entity\TaskSwipe;
use App\CQRS\BaseCommand;

class CreateTaskSwipe extends BaseCommand
{
    public function __construct(
        public TaskSwipe $taskSwipe
    ) {
    }
}

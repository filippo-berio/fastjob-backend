<?php

namespace App\Core\Data\Command\TaskSwipe\Create;

use App\Core\Entity\TaskSwipe;
use App\CQRS\BaseCommand;

class CreateTaskSwipe extends BaseCommand
{
    public function __construct(
        public TaskSwipe $taskSwipe
    ) {
    }
}

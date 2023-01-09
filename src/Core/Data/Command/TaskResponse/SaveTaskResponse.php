<?php

namespace App\Core\Data\Command\TaskResponse;

use App\Core\Entity\TaskResponse;
use App\CQRS\BaseCommand;

class SaveTaskResponse extends BaseCommand
{
    public function __construct(
        public TaskResponse $taskResponse
    ) {
    }
}

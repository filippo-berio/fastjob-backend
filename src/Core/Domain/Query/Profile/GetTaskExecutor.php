<?php

namespace App\Core\Domain\Query\Profile;

use App\Core\Domain\Entity\Task;
use App\CQRS\QueryInterface;

class GetTaskExecutor implements QueryInterface
{
    public function __construct(
        public Task $task
    ) {
    }
}

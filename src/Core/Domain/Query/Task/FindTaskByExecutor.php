<?php

namespace App\Core\Domain\Query\Task;

use App\Core\Domain\Entity\Profile;
use App\CQRS\QueryInterface;

class FindTaskByExecutor implements QueryInterface
{
    public function __construct(
        public Profile $executor,
    ) {
    }
}

<?php

namespace App\Core\Domain\Query\Task;

use App\CQRS\QueryInterface;

class FindTaskByIds implements QueryInterface
{
    public function __construct(
        public array $ids
    ) {
    }
}

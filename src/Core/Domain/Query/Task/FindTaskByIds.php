<?php

namespace App\Core\Domain\Query\Task;

use App\Lib\CQRS\QueryInterface;

class FindTaskByIds implements QueryInterface
{
    public function __construct(
        public array $ids
    ) {
    }
}

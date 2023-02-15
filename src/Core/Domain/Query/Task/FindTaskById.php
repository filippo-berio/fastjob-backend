<?php

namespace App\Core\Domain\Query\Task;

use App\Lib\CQRS\QueryInterface;

class FindTaskById implements QueryInterface
{
    public function __construct(
        public int $id
    ) {
    }
}

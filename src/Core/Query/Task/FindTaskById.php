<?php

namespace App\Core\Query\Task;

use App\CQRS\BaseQuery;

class FindTaskById extends BaseQuery
{
    public function __construct(
        public int $id
    ) {
    }
}

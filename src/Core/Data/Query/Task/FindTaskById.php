<?php

namespace App\Core\Data\Query\Task;

use App\CQRS\BaseQuery;

class FindTaskById extends BaseQuery
{
    public function __construct(
        public int $id
    ) {
    }
}

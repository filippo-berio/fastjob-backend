<?php

namespace App\Core\Domain\Query\Task\FindByIds;

use App\CQRS\BaseQuery;

class FindTaskByIds extends BaseQuery
{
    public function __construct(
        public array $ids
    ) {
    }
}

<?php

namespace App\Core\Query\Profile\FindProfileById;

use App\CQRS\BaseQuery;

class FindProfileById extends BaseQuery
{
    public function __construct(
        public int $id
    ) {
    }
}

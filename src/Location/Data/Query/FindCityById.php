<?php

namespace App\Location\Data\Query;

use App\CQRS\BaseQuery;

class FindCityById extends BaseQuery
{
    public function __construct(
        public int $id
    ) {
    }
}

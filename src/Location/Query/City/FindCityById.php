<?php

namespace App\Location\Query\City;

use App\CQRS\BaseQuery;

class FindCityById extends BaseQuery
{
    public function __construct(
        public int $id
    ) {
    }
}

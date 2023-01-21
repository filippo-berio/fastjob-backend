<?php

namespace App\Location\Query\Address\FindByCityAddress;

use App\CQRS\BaseQuery;
use App\Location\Entity\City;

class FindAddressByCityAndTitle extends BaseQuery
{
    public function __construct(
        public City   $city,
        public string $title,
    ) {
    }
}

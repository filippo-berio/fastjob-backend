<?php

namespace App\Auth\Query\User;

use App\CQRS\BaseQuery;

class FindUserByPhone extends BaseQuery
{
    public function __construct(
        public string $phone
    ) {
    }
}

<?php

namespace App\Auth\Query\User\FindByPhone;

use App\Lib\CQRS\BaseQuery;

class FindUserByPhone extends BaseQuery
{
    public function __construct(
        public string $phone
    ) {
    }
}

<?php

namespace App\Auth\Query\User;

use App\CQRS\BaseQuery;

class FindUserById extends BaseQuery
{
    public function __construct(public int $id)
    {
    }
}

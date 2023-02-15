<?php

namespace App\Auth\Query\User\FindById;

use App\Lib\CQRS\BaseQuery;

class FindUserById extends BaseQuery
{
    public function __construct(public int $id)
    {
    }
}

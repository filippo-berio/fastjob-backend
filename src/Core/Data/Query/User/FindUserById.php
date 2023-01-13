<?php

namespace App\Core\Data\Query\User;

use App\CQRS\BaseQuery;

class FindUserById extends BaseQuery
{
    public function __construct(public int $id)
    {
    }
}

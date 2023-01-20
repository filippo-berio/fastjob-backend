<?php

namespace App\Core\Query\Profile\FindByUser;

use App\Auth\Entity\User;
use App\CQRS\BaseQuery;

class FindProfileByUser extends BaseQuery
{
    public function __construct(
        public User $user
    ) {
    }
}

<?php

namespace App\Auth\Query\RefreshToken\FindByUser;

use App\Auth\Entity\User;
use App\CQRS\BaseQuery;

class FindRefreshTokenByUser extends BaseQuery
{
    public function __construct(
        public User $user,
    ) {
    }
}

<?php

namespace App\Core\Data\Query\RefreshToken\FindByUser;

use App\Core\Entity\User;
use App\CQRS\BaseQuery;

class FindRefreshTokenByUser extends BaseQuery
{
    public function __construct(
        public User $user,
    ) {
    }
}

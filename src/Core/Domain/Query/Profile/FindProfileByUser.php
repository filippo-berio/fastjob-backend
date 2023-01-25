<?php

namespace App\Core\Domain\Query\Profile;

use App\Auth\Entity\User;
use App\CQRS\QueryInterface;

class FindProfileByUser implements QueryInterface
{
    public function __construct(
        public User $user
    ) {
    }
}

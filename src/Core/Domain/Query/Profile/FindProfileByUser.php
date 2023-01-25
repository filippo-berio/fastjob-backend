<?php

namespace App\Core\Domain\Query\Profile;

use App\Core\Domain\Entity\User;
use App\CQRS\QueryInterface;

class FindProfileByUser implements QueryInterface
{
    public function __construct(
        public User $user
    ) {
    }
}

<?php

namespace App\Core\Domain\Query\Profile;

use App\Core\Domain\Entity\User;
use App\Lib\CQRS\QueryInterface;

class FindProfileByUser implements QueryInterface
{
    public function __construct(
        public User $user
    ) {
    }
}

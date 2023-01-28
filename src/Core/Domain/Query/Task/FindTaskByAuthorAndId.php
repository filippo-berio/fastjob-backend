<?php

namespace App\Core\Domain\Query\Task;

use App\Core\Domain\Entity\Profile;
use App\CQRS\QueryInterface;

class FindTaskByAuthorAndId implements QueryInterface
{
    public function __construct(
        public Profile $profile,
        public int $id
    ) {
    }
}

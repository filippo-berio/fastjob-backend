<?php

namespace App\Core\Domain\Query\Task;

use App\Core\Domain\Entity\Profile;
use App\CQRS\QueryInterface;

class FindWaitTaskByAuthor implements QueryInterface
{
    public function __construct(
        public Profile $profile,
    ) {
    }
}

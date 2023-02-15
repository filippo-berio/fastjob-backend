<?php

namespace App\Core\Domain\Query\Task;

use App\Core\Domain\Entity\Profile;
use App\Lib\CQRS\QueryInterface;

class FindTaskByAuthor implements QueryInterface
{
    public function __construct(
        public Profile $profile,
    ) {
    }
}

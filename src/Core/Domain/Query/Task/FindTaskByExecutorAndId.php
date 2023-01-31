<?php

namespace App\Core\Domain\Query\Task;

use App\Core\Domain\Entity\Profile;
use App\CQRS\QueryInterface;

class FindTaskByExecutorAndId implements QueryInterface
{
    public function __construct(
        public Profile $executor,
        public int     $id,
    )
    {
    }
}

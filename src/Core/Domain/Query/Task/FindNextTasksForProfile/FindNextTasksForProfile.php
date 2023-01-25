<?php

namespace App\Core\Domain\Query\Task\FindNextTasksForProfile;

use App\Core\Domain\Entity\Profile;
use App\CQRS\BaseQuery;

class FindNextTasksForProfile extends BaseQuery
{
    public function __construct(
        public Profile $profile,
        public int $count,
    ) {
    }
}

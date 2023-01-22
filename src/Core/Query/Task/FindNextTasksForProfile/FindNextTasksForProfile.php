<?php

namespace App\Core\Query\Task\FindNextTasksForProfile;

use App\Core\Entity\Profile;
use App\CQRS\BaseQuery;

class FindNextTasksForProfile extends BaseQuery
{
    public function __construct(
        public Profile $profile,
        public int $count
    ) {
    }
}

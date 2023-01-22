<?php

namespace App\Core\Query\Task\FindByProfile;

use App\Core\Entity\Profile;
use App\CQRS\BaseQuery;

class FindTaskByEmployer extends BaseQuery
{
    public function __construct(
        public Profile $profile,
    ) {
    }
}

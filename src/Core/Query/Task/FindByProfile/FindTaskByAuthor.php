<?php

namespace App\Core\Query\Task\FindByProfile;

use App\Core\Entity\Profile;
use App\CQRS\BaseQuery;

class FindTaskByAuthor extends BaseQuery
{
    public function __construct(
        public Profile $profile,
    ) {
    }
}

<?php

namespace App\Core\Query\TaskSwipe\FindByProfile;

use App\Core\Entity\Profile;
use App\CQRS\BaseQuery;

class FindTaskSwipeByProfile extends BaseQuery
{
    public function __construct(
        public Profile $profile,
    ) {
    }
}

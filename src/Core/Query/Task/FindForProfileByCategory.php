<?php

namespace App\Core\Query\Task;

use App\Core\Entity\Profile;
use App\CQRS\BaseQuery;

class FindForProfileByCategory extends BaseQuery
{
    public function __construct(
        public Profile $profile
    ) {
    }
}

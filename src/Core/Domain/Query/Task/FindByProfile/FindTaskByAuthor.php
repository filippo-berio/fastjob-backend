<?php

namespace App\Core\Domain\Query\Task\FindByProfile;

use App\Core\Domain\Entity\Profile;
use App\CQRS\BaseQuery;

class FindTaskByAuthor extends BaseQuery
{
    public function __construct(
        public Profile $profile,
    ) {
    }
}

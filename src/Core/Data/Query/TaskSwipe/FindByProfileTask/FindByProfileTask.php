<?php

namespace App\Core\Data\Query\TaskSwipe\FindByProfileTask;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\CQRS\BaseQuery;

class FindByProfileTask extends BaseQuery
{
    public function __construct(
        public Profile $profile,
        public Task $task
    ) {
    }
}

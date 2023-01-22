<?php

namespace App\Core\Query\TaskSwipe\FindByProfileTask;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\CQRS\BaseQuery;

class FindTaskSwipeByProfileAndTask extends BaseQuery
{
    public function __construct(
        public Profile $profile,
        public Task $task,
    ) {
    }
}

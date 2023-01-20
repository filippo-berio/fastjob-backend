<?php

namespace App\Core\Query\ExecutorSwipe\FindByProfileTask;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\CQRS\BaseQuery;

class FindExecutorSwipeByProfileTask extends BaseQuery
{
    public function __construct(
        public Profile $profile,
        public Task $task
    ) {
    }
}

<?php

namespace App\Core\Data\Query\TaskResponse;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\CQRS\BaseQuery;

class FindByProfileTask extends BaseQuery
{
    public function __construct(
        public Task $task,
        public Profile $profile,
    ) {
    }
}

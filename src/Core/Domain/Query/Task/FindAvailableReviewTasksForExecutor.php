<?php

namespace App\Core\Domain\Query\Task;

use App\Core\Domain\Entity\Profile;
use App\Lib\CQRS\QueryInterface;

/** @deprecated  */
class FindAvailableReviewTasksForExecutor implements QueryInterface
{
    public function __construct(
        public Profile $executor,
    ) {
    }

}

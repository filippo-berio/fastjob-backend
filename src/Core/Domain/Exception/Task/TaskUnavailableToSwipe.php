<?php

namespace App\Core\Domain\Exception\Task;

use App\Core\Domain\Exception\BaseException;

class TaskUnavailableToSwipe extends BaseException
{
    public function __construct()
    {
        parent::__construct('Нельзя свайпнуть эту задачу', 403);
    }
}

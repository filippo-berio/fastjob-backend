<?php

namespace App\Core\Exception\Task;

use App\Core\Exception\BaseException;

class TaskUnavailableToSwipe extends BaseException
{
    public function __construct()
    {
        parent::__construct('Нельзя свайпнуть эту задачу', 403);
    }
}

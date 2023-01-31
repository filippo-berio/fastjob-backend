<?php

namespace App\Core\Domain\Exception\Task;

use App\Core\Domain\Exception\BaseException;

class TaskCancelException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Невозможно отменить задачу', 403);
    }
}

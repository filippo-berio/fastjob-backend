<?php

namespace App\Core\Domain\Exception\Task;

use App\Core\Domain\Exception\BaseException;

class TaskNotInWorkException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Задача не была взята в работу', 403);
    }
}

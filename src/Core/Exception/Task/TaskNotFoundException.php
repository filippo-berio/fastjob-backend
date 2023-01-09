<?php

namespace App\Core\Exception\Task;

use Exception;

class TaskNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Не найдена задача', 404);
    }
}

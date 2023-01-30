<?php

namespace App\Core\Application\Exception\Task;

use App\Core\Application\Exception\CoreApplicationException;

class TaskNotFoundException extends CoreApplicationException
{
    public function __construct()
    {
        parent::__construct('Задача не найдена', 404);
    }
}

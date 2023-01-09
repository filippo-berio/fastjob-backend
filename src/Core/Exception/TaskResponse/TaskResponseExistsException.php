<?php

namespace App\Core\Exception\TaskResponse;

use App\Core\Exception\BaseException;

class TaskResponseExistsException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Данный профиль уже отреагировал на задачу', 403);
    }
}

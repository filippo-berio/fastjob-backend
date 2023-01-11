<?php

namespace App\Core\Exception\TaskSwipe;

use App\Core\Exception\BaseException;

class TaskSwipeExistsException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Данный профиль уже свайпнул задачу', 403);
    }
}

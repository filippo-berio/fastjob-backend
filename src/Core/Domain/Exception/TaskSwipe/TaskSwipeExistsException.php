<?php

namespace App\Core\Domain\Exception\TaskSwipe;

use App\Core\Domain\Exception\BaseException;

class TaskSwipeExistsException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Данный профиль уже свайпнул задачу', 403);
    }
}

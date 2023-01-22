<?php

namespace App\Core\Exception\Task;

use App\Core\Exception\BaseException;

class NextTaskCountExceedsLimitException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Превышен лимит', 403);
    }
}

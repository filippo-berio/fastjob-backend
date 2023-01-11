<?php

namespace App\Core\Exception\ExecutorSwipe;

use App\Core\Exception\BaseException;
use Throwable;

class ExecutorSwipeExistsException extends BaseException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Для задачи уже свайпнули этого исполнителя', 403, $previous);
    }
}

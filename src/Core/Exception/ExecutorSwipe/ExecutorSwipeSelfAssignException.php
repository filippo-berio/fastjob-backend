<?php

namespace App\Core\Exception\ExecutorSwipe;

use App\Core\Exception\BaseException;
use Throwable;

class ExecutorSwipeSelfAssignException extends BaseException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Нельзя назначить задачу её автору', 403, $previous);
    }
}

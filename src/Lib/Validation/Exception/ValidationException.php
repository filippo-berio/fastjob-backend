<?php

namespace App\Lib\Validation\Exception;

use App\Core\Domain\Exception\BaseException;

class ValidationException extends BaseException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 403);
    }
}

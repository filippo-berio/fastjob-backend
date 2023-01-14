<?php

namespace App\Core\Exception;

class ValidationException extends BaseException
{
    public function __construct(string $message)
    {
        parent::__construct($message, 403);
    }
}

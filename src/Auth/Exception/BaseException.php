<?php

namespace App\Auth\Exception;

use Exception;

class BaseException extends Exception
{
    public int $customCode;

    public function __construct(string $message, int $code, int $customCode = 1)
    {
        parent::__construct($message, $code);
        $this->customCode = $customCode;
    }
}

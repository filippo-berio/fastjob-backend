<?php

namespace App\Authistic\Exception;

use Exception;
use Throwable;

class AuthisticException extends Exception
{
    public function __construct(string $message, int $code, ?Throwable $prev = null)
    {
        parent::__construct($message, $code, $prev);
    }
}

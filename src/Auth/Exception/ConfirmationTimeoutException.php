<?php

namespace App\Auth\Exception;

class ConfirmationTimeoutException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Не прошел таймаут', 403);
    }
}

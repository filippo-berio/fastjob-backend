<?php

namespace App\Auth\Exception;

class InvalidConfirmationCodeException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Неверный код подтверждения', 403);
    }
}

<?php

namespace App\Core\Exception\Auth;

use App\Core\Exception\BaseException;

class InvalidConfirmationCodeException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Неверный код подтверждения', 403);
    }
}

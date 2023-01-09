<?php

namespace App\Authistic\Exception;

use Exception;

class RegisterException extends Exception
{
    public function __construct()
    {
        parent::__construct('Произошла ошибка при регистрации', 403);
    }
}

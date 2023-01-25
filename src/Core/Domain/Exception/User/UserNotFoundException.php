<?php

namespace App\Core\Domain\Exception\User;

use App\Core\Domain\Exception\BaseException;

class UserNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Пользователь не найден', 404);
    }
}

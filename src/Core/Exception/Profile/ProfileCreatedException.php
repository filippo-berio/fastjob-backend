<?php

namespace App\Core\Exception\Profile;

use App\Core\Exception\BaseException;

class ProfileCreatedException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Профиль этого пользователя уже создан', 403);
    }
}

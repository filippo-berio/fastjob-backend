<?php

namespace App\Core\Domain\Exception\Profile;

use App\Core\Domain\Exception\BaseException;

class ProfileCreatedException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Профиль этого пользователя уже создан', 403);
    }
}

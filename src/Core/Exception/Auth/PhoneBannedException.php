<?php

namespace App\Core\Exception\Auth;

use App\Core\Exception\BaseException;

class PhoneBannedException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Телефон заблокирован', 403);
    }
}

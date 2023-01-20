<?php

namespace App\Auth\Exception;

class PhoneBannedException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Телефон заблокирован', 403);
    }
}

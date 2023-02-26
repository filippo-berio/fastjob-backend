<?php

namespace App\Auth\Exception;

class PhoneBannedException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Вы превысили число попыток. Попробуйте позже.', 403, 51);
    }
}

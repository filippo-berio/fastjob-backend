<?php

namespace App\Core\Exception\Profile;

use App\Core\Exception\BaseException;

class ProfileTooYoungException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Ты еще очень и очень мал, сынок', 403);
    }
}

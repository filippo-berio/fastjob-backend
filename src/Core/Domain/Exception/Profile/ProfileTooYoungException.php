<?php

namespace App\Core\Domain\Exception\Profile;

use App\Core\Domain\Exception\BaseException;

class ProfileTooYoungException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Ты еще очень и очень мал, сынок', 403);
    }
}

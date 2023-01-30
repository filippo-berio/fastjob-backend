<?php

namespace App\Review\Application\Exception\Profile;

use App\Review\Domain\Exception\ReviewException;

class ProfileNotFoundException extends ReviewException
{
    public function __construct()
    {
        parent::__construct('Профиль не найден', 404);
    }
}

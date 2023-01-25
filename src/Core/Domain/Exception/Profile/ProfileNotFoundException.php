<?php

namespace App\Core\Domain\Exception\Profile;

use App\Core\Domain\Exception\BaseException;
use Throwable;

class ProfileNotFoundException extends BaseException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('Профиль не найден', 404, $previous);
    }
}

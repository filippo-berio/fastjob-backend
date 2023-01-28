<?php

namespace App\Core\Domain\Exception\SwipeMatch;

use App\Core\Domain\Exception\BaseException;

class SwipeMatchNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Мэтч не найден', 404);
    }
}

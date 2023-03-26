<?php

namespace App\Chat\Exception;

use App\Core\Domain\Exception\BaseException;

class DirectChatNotFound extends BaseException
{
    public function __construct()
    {
        parent::__construct('Чат не найден', 404);
    }
}

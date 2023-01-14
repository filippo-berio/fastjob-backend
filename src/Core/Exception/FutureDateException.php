<?php

namespace App\Core\Exception;

class FutureDateException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Вы не можете указать будущую дату', 403);
    }
}

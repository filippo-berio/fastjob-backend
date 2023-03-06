<?php

namespace App\Core\Application\Exception;


class NotFoundException extends CoreApplicationException
{
    public function __construct(string $message = 'Не найдено')
    {
        parent::__construct($message, 404);
    }
}

<?php

namespace App\Location\Exception;


class CityNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Населенный пункт не найден', 404);
    }
}

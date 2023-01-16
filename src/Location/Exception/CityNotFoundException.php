<?php

namespace App\Location\Exception;

use Exception;

class CityNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Населенный пункт не найден', 404);
    }
}

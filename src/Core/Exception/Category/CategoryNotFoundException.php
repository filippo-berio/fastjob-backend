<?php

namespace App\Core\Exception\Category;

use App\Core\Exception\BaseException;

class CategoryNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Категория не найдена', 404);
    }
}

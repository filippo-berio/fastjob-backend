<?php

namespace App\Core\Domain\Exception\Category;

use App\Core\Domain\Exception\BaseException;

class CategoryNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Категория не найдена', 404);
    }
}

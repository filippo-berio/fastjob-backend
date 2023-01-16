<?php

namespace App\Validation\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraints\Regex;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Name extends Regex
{
    public $message = 'Некорректное имя';
    public $pattern = '/^[\sA-zА-я-]*$/';

    public function __construct()
    {
        parent::__construct($this->pattern);
    }
}

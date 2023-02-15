<?php


namespace App\Lib\Validation\Constraints;


use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Phone extends Constraint
{
    public string $message = 'Неверно указан номер телефона. Нужный формат - +7хххххххххх';
}

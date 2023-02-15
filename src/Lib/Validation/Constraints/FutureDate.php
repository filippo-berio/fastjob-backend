<?php

namespace App\Lib\Validation\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class FutureDate extends Constraint
{
    public string $message = 'Вы не можете указать будущую дату';
}

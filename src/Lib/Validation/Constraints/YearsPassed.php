<?php

namespace App\Lib\Validation\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class YearsPassed extends Constraint
{
    public function __construct(
        public int $shouldPass,
        public ?string $message = null,
    ) {
        parent::__construct();
    }

    public function getMessage(): string
    {
        return $this->message ?? "Должно пройти $this->shouldPass лет";
    }
}

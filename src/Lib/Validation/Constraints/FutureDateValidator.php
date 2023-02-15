<?php

namespace App\Lib\Validation\Constraints;

use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FutureDateValidator extends ConstraintValidator
{

    /**
     * @param DateTimeInterface $value
     * @param FutureDate $constraint
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (new DateTimeImmutable() > $value) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->format('d.m.Y'))
                ->addViolation();
        }
    }
}

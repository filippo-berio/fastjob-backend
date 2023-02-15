<?php

namespace App\Lib\Validation\Constraints;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class YearsPassedValidator extends ConstraintValidator
{
    /**
     * @param \DateTimeInterface $value
     * @param YearsPassed $constraint
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if ($value->diff(new DateTimeImmutable())->y < $constraint->shouldPass) {
            $this->context->buildViolation($constraint->getMessage())
                ->setParameter('{{ value }}', $value->format('d.m.Y'))
                ->addViolation();
        }
    }
}

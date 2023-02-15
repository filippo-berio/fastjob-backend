<?php

namespace App\Lib\Validation\Constraints;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OldDateValidator extends ConstraintValidator
{
    /**
     * @param DateTimeInterface $value
     * @param OldDate $constraint
     * @return void
     * @throws Exception
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (new DateTimeImmutable() < $value) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->format('d.m.Y'))
                ->addViolation();
        }
    }
}

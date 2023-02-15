<?php


namespace App\Lib\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PhoneValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        /** @var Phone $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (!preg_match('/^\+7\d{10}$/', $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}

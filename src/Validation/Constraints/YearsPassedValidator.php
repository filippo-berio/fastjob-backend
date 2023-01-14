<?php

namespace App\Validation\Constraints;

use App\Core\Service\AgeService;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class YearsPassedValidator extends ConstraintValidator
{
    public function __construct(
        private AgeService $ageService,
    ) {
    }

    /**
     * @param DateTimeImmutable $value
     * @param YearsPassed $constraint
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $diff = $this->ageService->calculateDiffYears(
            $value,
            new DateTimeImmutable()
        );
        if ($diff < $constraint->shouldPass) {
            $this->context->buildViolation($constraint->getMessage())
                ->setParameter('{{ value }}', $value->format('d.m.Y'))
                ->addViolation();
        }
    }
}

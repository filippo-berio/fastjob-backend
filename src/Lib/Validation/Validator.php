<?php

namespace App\Lib\Validation;

use App\Lib\Validation\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

class Validator implements ValidatorInterface
{
    public function __construct(private SymfonyValidatorInterface $validator)
    {
    }

    public function validate($object): void
    {
        $errors = $this->validator->validate($object);
        if ($errors->count() > 0) {
            $validationErrors = [];
            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $validationErrors[$error->getPropertyPath()] = $error->getMessage();
            }

            throw new ValidationException(implode(', ', $validationErrors));
        }
    }
}

<?php

namespace App\Validation;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

class Validator implements ValidatorInterface
{
    public function __construct(private SymfonyValidatorInterface $validator)
    {
    }

    public function validate($object): array
    {
        $validationErrors = [];
        $errors           = $this->validator->validate($object);
        if ($errors->count() > 0) {
            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $validationErrors[$error->getPropertyPath()] = $error->getMessage();
            }
        }

        return $validationErrors;
    }
}

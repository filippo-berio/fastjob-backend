<?php

namespace App\Validation;

interface ValidatorInterface
{
    public function validate($object): array;
}

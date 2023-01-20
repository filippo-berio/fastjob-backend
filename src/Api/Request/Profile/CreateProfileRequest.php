<?php

namespace App\Api\Request\Profile;

use App\Api\Request\RequestInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateProfileRequest implements RequestInterface
{
    #[NotBlank]
    public ?string $firstName = null;

    #[NotBlank]
    #[Date]
    public ?string $birthDate = null;
}

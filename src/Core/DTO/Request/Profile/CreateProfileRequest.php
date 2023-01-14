<?php

namespace App\Core\DTO\Request\Profile;

use App\Core\DTO\Request\RequestInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateProfileRequest implements RequestInterface
{
    #[NotBlank]
    public ?string $firstName;

    #[NotBlank]
    #[Date]
    public ?string $birthDate;
}

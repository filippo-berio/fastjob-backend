<?php

namespace App\Core\DTO\Request\Profile;

use App\Core\DTO\Request\RequestInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class UpdateProfileRequest implements RequestInterface
{
    #[NotBlank]
    public ?string $firstName = null;

    public ?string $lastName = null;

    public ?string $description = null;

    #[Type('integer')]
    public ?string $cityId = null;
}

<?php

namespace App\Api\Request\Profile;

use App\Api\Request\RequestInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class UpdateProfileRequest implements RequestInterface
{
    #[NotBlank]
    public ?string $firstName = null;

    #[All([
        new Type('integer')
    ])]
    public array $categoryIds = [];

    public ?string $lastName = null;

    public ?string $description = null;

    #[Type('integer')]
    public ?string $cityId = null;
}

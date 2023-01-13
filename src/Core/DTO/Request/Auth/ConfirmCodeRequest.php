<?php

namespace App\Core\DTO\Request\Auth;

use App\Core\DTO\Request\RequestInterface;
use App\Validation\Constraints\Phone;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConfirmCodeRequest implements RequestInterface
{
    #[NotBlank]
    #[Phone]
    public ?string $phone;

    #[NotBlank]
    public ?string $code;
}

<?php

namespace App\Api\Request\Auth;

use App\Api\Request\RequestInterface;
use App\Lib\Validation\Constraints\Phone;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConfirmCodeRequest implements RequestInterface
{
    #[NotBlank]
    #[Phone]
    public ?string $phone;

    #[NotBlank]
    public ?string $code;
}

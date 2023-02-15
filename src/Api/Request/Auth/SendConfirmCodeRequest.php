<?php

namespace App\Api\Request\Auth;

use App\Api\Request\RequestInterface;
use App\Lib\Validation\Constraints\Phone;
use Symfony\Component\Validator\Constraints\NotBlank;

class SendConfirmCodeRequest implements RequestInterface
{
    #[NotBlank]
    #[Phone]
    public ?string $phone;
}

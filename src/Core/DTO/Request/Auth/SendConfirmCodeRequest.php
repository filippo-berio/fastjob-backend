<?php

namespace App\Core\DTO\Request\Auth;

use App\Core\DTO\Request\RequestInterface;
use App\Validation\Constraints\Phone;
use Symfony\Component\Validator\Constraints\NotBlank;

class SendConfirmCodeRequest implements RequestInterface
{
    #[NotBlank]
    #[Phone]
    public ?string $phone;
}

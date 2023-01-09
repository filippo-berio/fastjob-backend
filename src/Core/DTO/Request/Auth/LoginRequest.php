<?php

namespace App\Core\DTO\Request\Auth;

use App\Core\DTO\Request\RequestInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginRequest implements RequestInterface
{
    #[NotBlank]
    public ?string $login;

    #[NotBlank]
    public ?string $password;
}

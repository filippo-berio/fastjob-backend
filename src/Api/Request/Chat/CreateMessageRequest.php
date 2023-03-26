<?php

namespace App\Api\Request\Chat;

use App\Api\Request\RequestInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateMessageRequest implements RequestInterface
{
    #[NotBlank]
    public int $chatId;

    #[NotBlank]
    public string $content;
}

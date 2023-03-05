<?php

namespace App\Api\Request\Executor;

use App\Api\Request\RequestInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class AcceptOfferRequest implements RequestInterface
{
    #[Type('numeric')]
    #[NotBlank]
    public string $taskId;
}

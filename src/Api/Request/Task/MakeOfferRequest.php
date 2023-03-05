<?php

namespace App\Api\Request\Task;

use App\Api\Request\RequestInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class MakeOfferRequest implements RequestInterface
{
    #[Type('numeric')]
    #[NotBlank]
    public string $taskId;

    #[Type('numeric')]
    #[NotBlank]
    public string $executorId;
}

<?php

namespace App\Core\DTO\Request\Task;

use App\Core\DTO\Request\RequestInterface;
use Symfony\Component\Validator\Constraints\Type;

class AcceptTaskRequest implements RequestInterface
{
    #[Type('integer')]
    public ?string $customPrice = null;
}

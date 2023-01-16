<?php

namespace App\Core\DTO\Request\Swipe;

use App\Core\DTO\Request\RequestInterface;
use App\Core\Entity\Swipe;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CreateTaskSwipeRequest implements RequestInterface
{
    #[NotBlank]
    #[Type('integer')]
    public ?string $taskId = null;

    #[NotBlank]
    #[Choice(Swipe::TYPES)]
    public ?string $type = null;

    #[Type('integer')]
    #[GreaterThan(0)]
    public ?string $customPrice = null;
}

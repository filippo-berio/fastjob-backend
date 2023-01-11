<?php

namespace App\Core\DTO\Request\Swipe;

use App\Core\DTO\Request\RequestInterface;
use App\Core\Entity\Swipe;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CreateExecutorSwipeRequest implements RequestInterface
{
    #[NotBlank]
    #[Type('integer')]
    public ?string $taskId;

    #[NotBlank]
    #[Type('integer')]
    public ?string $executorId;

    #[NotBlank]
    #[Choice(Swipe::TYPES)]
    public ?string $type;
}

<?php

namespace App\Api\Request\Swipe;

use App\Api\Request\RequestInterface;
use App\Core\Entity\Swipe;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CreateExecutorSwipeRequest implements RequestInterface
{
    #[NotBlank]
    #[Type('integer')]
    public ?string $taskId = null;

    #[NotBlank]
    #[Type('integer')]
    public ?string $executorId = null;

    #[NotBlank]
    #[Choice(Swipe::TYPES)]
    public ?string $type = null;
}
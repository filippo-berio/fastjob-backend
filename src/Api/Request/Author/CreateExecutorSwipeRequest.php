<?php

namespace App\Api\Request\Author;

use App\Api\Request\RequestInterface;
use App\Core\Domain\Entity\Swipe;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CreateExecutorSwipeRequest implements RequestInterface
{
    #[NotBlank]
    #[Type('numeric')]
    public ?string $taskId = null;

    #[NotBlank]
    #[Type('numeric')]
    public ?string $executorId = null;

    #[NotBlank]
    #[Choice(Swipe::TYPES)]
    public ?string $type = null;
}

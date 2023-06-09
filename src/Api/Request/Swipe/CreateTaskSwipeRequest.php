<?php

namespace App\Api\Request\Swipe;

use App\Api\Request\RequestInterface;
use App\Core\Domain\Entity\Swipe;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CreateTaskSwipeRequest implements RequestInterface
{
    #[NotBlank]
    #[Type('numeric')]
    public ?string $taskId = null;

    #[NotBlank]
    #[Choice(Swipe::TYPES)]
    public ?string $type = null;

    #[Type('numeric')]
    #[GreaterThan(0)]
    public ?string $customPrice = null;
}

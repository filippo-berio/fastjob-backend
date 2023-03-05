<?php

namespace App\Api\Request\Author;

use App\Api\Request\RequestInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class FinishTaskRequest implements RequestInterface
{
    #[NotBlank]
    public int $taskId;

    #[NotBlank]
    #[Range(min: 1, max: 5)]
    public int $rating;

    #[Length(max: 255)]
    public ?string $comment = null;
}

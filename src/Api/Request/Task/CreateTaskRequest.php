<?php

namespace App\Api\Request\Task;

use App\Api\Request\RequestInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CreateTaskRequest implements RequestInterface
{
    #[NotBlank]
    public ?string $title;

    #[All(
        new Type('integer')
    )]
    public array $categoryIds = [];

    public ?string $description = null;

    #[Type('integer')]
    public ?string $price = null;

    #[Collection([
        'cityId' => [
            new NotBlank(),
            new Type('integer')
        ],
        'title' => [
            new NotBlank()
        ]
    ])]
    public ?array $address = null;

    #[Date]
    public ?string $deadline = null;
}

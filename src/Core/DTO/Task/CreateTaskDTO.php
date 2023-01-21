<?php

namespace App\Core\DTO\Task;

use App\Core\DTO\Address\AddressPlain;
use App\Core\Entity\Category;
use App\Core\Entity\Profile;
use App\Validation\Constraints\FutureDate;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Type;

readonly class CreateTaskDTO
{
    public Profile $profile;

    /** @var Category[] */
    #[All(
        new Type(Category::class)
    )]
    public array $categories;

    public string $title;

    public ?string $description;

    public ?int $price;

    public ?AddressPlain $addressPlain;

    #[FutureDate]
    public ?DateTimeInterface $deadline;

    public function __construct(
        Profile $profile,
        string $title,
        array $categories = [],
        ?string $description = null,
        ?int $price = null,
        ?AddressPlain $addressPlain = null,
        ?DateTimeInterface $deadline = null,
    )
    {
        $this->profile = $profile;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->addressPlain = $addressPlain;
        $this->deadline = $deadline;
        $this->categories = $categories;
    }
}

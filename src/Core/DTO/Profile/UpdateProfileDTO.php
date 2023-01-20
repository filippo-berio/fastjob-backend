<?php

namespace App\Core\DTO\Profile;

use App\Core\Entity\Category;
use App\Location\Entity\City;
use App\Validation\Constraints\Name;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Type;

readonly class UpdateProfileDTO
{
    #[Name]
    public string $firstName;

    #[Name]
    public ?string $lastName;

    public ?string $description;

    public ?City $city;

    /** @var Category[] */
    #[All([
        new Type(Category::class)
    ])]
    public array $categories;

    public function __construct(
        string $firstName,
        array $categories = [],
        ?string $lastName = null,
        ?string $description = null,
        ?City $city = null,
    ) {
        $this->firstName = $firstName;
        $this->categories = $categories;
        $this->lastName = $lastName;
        $this->description = $description;
        $this->city = $city;
    }
}

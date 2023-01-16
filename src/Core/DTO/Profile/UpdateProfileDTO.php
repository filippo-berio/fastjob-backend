<?php

namespace App\Core\DTO\Profile;

use App\Location\Entity\City;
use App\Validation\Constraints\Name;

readonly class UpdateProfileDTO
{
    #[Name]
    public string $firstName;

    #[Name]
    public ?string $lastName;

    public ?string $description;

    public ?City $city;

    public function __construct(
        string $firstName,
        ?string $lastName = null,
        ?string $description = null,
        ?City $city = null,
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->description = $description;
        $this->city = $city;
    }
}

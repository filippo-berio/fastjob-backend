<?php

namespace App\Location\DTO\Address;

use App\Location\Entity\City;

readonly class CreateAddressDTO
{
    public function __construct(
        public City $city,
        public string $title
    ) {
    }
}

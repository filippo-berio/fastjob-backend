<?php

namespace App\Location\Service\Address;

use App\Location\Entity\City;
use App\Location\Entity\ValueObject\Coordinates;

class GetCoordinatesService
{
    public function __construct()
    {
    }

    public function get(City $city, string $address): Coordinates
    {
        return new Coordinates(56.8519, 60.6122);
    }
}

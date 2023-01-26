<?php

namespace App\Core\Domain\Contract\City;


use App\Location\Entity\City;

interface GetCityByIdServiceInterface
{
    public function get(int $id): ?City;
}

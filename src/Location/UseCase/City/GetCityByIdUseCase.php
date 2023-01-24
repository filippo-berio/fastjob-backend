<?php

namespace App\Location\UseCase\City;

use App\Location\Entity\City;
use App\Location\Repository\CityRepository;

class GetCityByIdUseCase
{
    public function __construct(
        private CityRepository $cityRepository,
    ) {
    }

    public function get(int $id): ?City
    {
        return $this->cityRepository->find($id);
    }
}

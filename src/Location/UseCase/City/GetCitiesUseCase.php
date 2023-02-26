<?php

namespace App\Location\UseCase\City;

use App\Location\Entity\City;
use App\Location\Repository\CityRepository;

class GetCitiesUseCase
{
    public function __construct(
        private CityRepository $cityRepository
    ) {
    }

    /**
     * @return City[]
     */
    public function findAll(): array
    {
        return $this->cityRepository->findAll();
    }
}

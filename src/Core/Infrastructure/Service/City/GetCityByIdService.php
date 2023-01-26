<?php

namespace App\Core\Infrastructure\Service\City;

use App\Core\Domain\Contract\City\GetCityByIdServiceInterface;
use App\Location\Entity\City;
use App\Location\UseCase\City\GetCityByIdUseCase;

class GetCityByIdService implements GetCityByIdServiceInterface
{
    public function __construct(
        private GetCityByIdUseCase $getCityByIdUseCase,
    ) {
    }

    public function get(int $id): ?City
    {
        return $this->getCityByIdUseCase->get($id);
    }
}

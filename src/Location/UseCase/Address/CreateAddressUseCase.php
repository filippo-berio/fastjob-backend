<?php

namespace App\Location\UseCase\Address;

use App\CQRS\Bus\QueryBusInterface;
use App\Location\DTO\Address\CreateAddressDTO;
use App\Location\Entity\Address;
use App\Location\Exception\CityNotFoundException;
use App\Location\Query\City\FindCityById;
use App\Location\Service\Address\GetOrCreateAddressService;

class CreateAddressUseCase
{
    public function __construct(
        private QueryBusInterface         $queryBus,
        private GetOrCreateAddressService $createAddressService,
    ) {
    }

    public function create(int $cityId, string $title): Address
    {
        $city = $this->queryBus->handle(new FindCityById($cityId));
        if (!$city) {
            throw new CityNotFoundException();
        }

        return $this->createAddressService->getOrCreate(new CreateAddressDTO($city, $title));
    }
}

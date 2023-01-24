<?php

namespace App\Location\UseCase\Address;

use App\Location\DTO\Address\CreateAddressDTO;
use App\Location\Entity\Address;
use App\Location\Exception\CityNotFoundException;
use App\Location\Repository\CityRepository;
use App\Location\Service\Address\GetOrCreateAddressService;

class CreateAddressUseCase
{
    public function __construct(
        private GetOrCreateAddressService $createAddressService,
        private CityRepository $cityRepository,
    ) {
    }

    public function create(int $cityId, string $title): Address
    {
        $city = $this->cityRepository->find($cityId);
        if (!$city) {
            throw new CityNotFoundException();
        }

        return $this->createAddressService->getOrCreate(new CreateAddressDTO($city, $title));
    }
}

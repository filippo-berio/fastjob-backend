<?php

namespace App\Location\Service\Address;

use App\Location\DTO\Address\CreateAddressDTO;
use App\Location\Entity\Address;
use App\Location\Repository\AddressRepository;

class GetOrCreateAddressService
{
    public function __construct(
        private GetCoordinatesService $getCoordinatesService,
        private AddressRepository $addressRepository,
    ) {
    }

    public function getOrCreate(CreateAddressDTO $createAddressDTO): Address
    {
        $existing = $this->addressRepository->findByCityAndTitle(
            $createAddressDTO->city,
            $createAddressDTO->title,
        );
        if ($existing) {
            return $existing;
        }

        $coordinates = $this->getCoordinatesService->get($createAddressDTO->city, $createAddressDTO->title);

        $address = new Address(
            $createAddressDTO->city,
            $createAddressDTO->title,
            $coordinates,
        );

        return $this->addressRepository->save($address);
    }
}

<?php

namespace App\Location\Service\Address;

use App\CQRS\Bus\CommandBusInterface;
use App\CQRS\Bus\QueryBusInterface;
use App\Location\Command\Address\Save\SaveAddress;
use App\Location\DTO\Address\CreateAddressDTO;
use App\Location\Entity\Address;
use App\Location\Query\Address\FindByCityAddress\FindAddressByCityAndTitle;

class GetOrCreateAddressService
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private GetCoordinatesService $getCoordinatesService,
    ) {
    }

    public function getOrCreate(CreateAddressDTO $createAddressDTO): Address
    {
        $existing = $this->queryBus->handle(new FindAddressByCityAndTitle(
            $createAddressDTO->city,
            $createAddressDTO->title,
        ));
        if ($existing) {
            return $existing;
        }

        $coordinates = $this->getCoordinatesService->get($createAddressDTO->city, $createAddressDTO->title);

        $address = new Address(
            $createAddressDTO->city,
            $createAddressDTO->title,
            $coordinates,
        );

        return $this->commandBus->handle(new SaveAddress($address));
    }
}

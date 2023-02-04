<?php

namespace App\Tests\Acceptance\Location\UseCase;

use App\DataFixtures\Location\AddressFixtures;
use App\DataFixtures\Location\CityFixtures;
use App\Location\Exception\CityNotFoundException;
use App\Location\UseCase\Address\CreateAddressUseCase;
use App\Tests\Acceptance\AcceptanceTest;

class CreateAddressTest extends AcceptanceTest
{
    /**
     * @dataProvider newAddressData
     */
    public function testNewAddress(int $cityId, string $title)
    {
        $this->bootContainer();
        $useCase = $this->getDependency(CreateAddressUseCase::class);
        $address = $useCase->create($cityId, $title);
        $this->assertNotNull($address->getId());
    }

    public function testExistingAddress()
    {
        $this->bootContainer();
        $useCase = $this->getDependency(CreateAddressUseCase::class);
        $address = $useCase->create(CityFixtures::CITY_1, AddressFixtures::ADDRESS_1_TITLE);
        $this->assertEquals(AddressFixtures::ADDRESS_1, $address->getId());
    }

    public function testCityNotExist()
    {
        $this->bootContainer();
        $useCase = $this->getDependency(CreateAddressUseCase::class);
        $this->expectException(CityNotFoundException::class);
        $useCase->create(CityFixtures::CITY_NOT_EXIST, 'address');
    }

    private function newAddressData()
    {
        return [
            [
                CityFixtures::CITY_2,
                'title',
            ],
            [
                CityFixtures::CITY_1,
                'title',
            ],
            [
                CityFixtures::CITY_3,
                AddressFixtures::ADDRESS_1_TITLE,
            ],
        ];
    }
}

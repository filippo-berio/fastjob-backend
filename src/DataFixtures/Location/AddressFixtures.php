<?php

namespace App\DataFixtures\Location;

use App\DataFixtures\BaseFixtures;
use App\Location\Entity\Address;
use App\Location\Entity\City;
use App\Location\Entity\ValueObject\Coordinates;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends BaseFixtures implements DependentFixtureInterface
{

    const ADDRESS_1 = 1;
    const ADDRESS_2 = 2;
    const ADDRESS_3 = 3;
    const ADDRESS_4 = 4;

    const ADDRESS_1_TITLE = 'ул. Пикадиля, 1';

    protected function getEntity(): string
    {
        return Address::class;
    }

    public function getDependencies()
    {
        return [
            CityFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        $city1 = $this->getReference(CityFixtures::CITY_1, City::class);
        $city3 = $this->getReference(CityFixtures::CITY_3, City::class);

        $this->save([
            $address1 = new Address($city1, self::ADDRESS_1_TITLE, new Coordinates(1, 1)),
            $address2 = new Address($city1, 'ул. Шляпина, 2', new Coordinates(1, 1)),
            $address3 = new Address($city3, 'ул. Акулина, 3', new Coordinates(1, 1)),
            $address4 = new Address($city3, 'ул. Шойгу, 4', new Coordinates(1, 1)),
        ], $manager);

        $this->addReference(self::ADDRESS_1, $address1);
        $this->addReference(self::ADDRESS_2, $address2);
        $this->addReference(self::ADDRESS_3, $address3);
        $this->addReference(self::ADDRESS_4, $address4);
    }
}

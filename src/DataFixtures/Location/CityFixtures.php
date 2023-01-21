<?php

namespace App\DataFixtures\Location;

use App\DataFixtures\BaseFixtures;
use App\Location\Entity\City;
use App\Location\Entity\Region;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CityFixtures extends BaseFixtures implements DependentFixtureInterface
{
    const CITY_1 = 1;
    const CITY_2 = 2;
    const CITY_3 = 3;
    const CITY_4 = 4;
    const CITY_NOT_EXIST = 400;

    protected function getEntity(): string
    {
        return City::class;
    }

    public function getDependencies()
    {
        return [
            RegionFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $region1 = $this->getReference(RegionFixtures::REGION_1, Region::class);
        $region2 = $this->getReference(RegionFixtures::REGION_2, Region::class);

        $this->save([
            $city1 = new City('Тобольск', $region1),
            $city2 = new City('Тюмень', $region1),
            $city3 = new City('Екатеринбург', $region2),
            $city4 = new City('Арамиль', $region2),
        ], $manager);

        $this->addReference(self::CITY_1, $city1);
        $this->addReference(self::CITY_2, $city2);
        $this->addReference(self::CITY_3, $city3);
        $this->addReference(self::CITY_4, $city4);
    }
}

<?php

namespace App\DataFixtures\Location;

use App\DataFixtures\BaseFixtures;
use App\Location\Entity\Region;
use Doctrine\Persistence\ObjectManager;

class RegionFixtures extends BaseFixtures
{
    const REGION_1 = 1;
    const REGION_2 = 2;

    protected function getEntity(): string
    {
        return Region::class;
    }

    public function load(ObjectManager $manager)
    {
        $this->save([
            $region1 = new Region('Тюменская область'),
            $region2 = new Region('Свердловская область'),
        ], $manager);

        $this->addReference(self::REGION_1, $region1);
        $this->addReference(self::REGION_2, $region2);
    }
}

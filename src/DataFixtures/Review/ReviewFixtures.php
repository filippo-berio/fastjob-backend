<?php

namespace App\DataFixtures\Review;

use App\DataFixtures\BaseFixtures;
use App\DataFixtures\Core\ProfileFixtures;
use App\Review\Domain\Entity\Profile;
use App\Review\Infrastructure\Entity\Review;
use Doctrine\Persistence\ObjectManager;

class ReviewFixtures extends BaseFixtures
{

    protected function getEntity(): string
    {
        return Review::class;
    }

    public function load(ObjectManager $manager)
    {
        $profile16 = new Profile(ProfileFixtures::PROFILE_16);
        $profile17 = new Profile(ProfileFixtures::PROFILE_17);

        $this->save([
            new Review($profile16, $profile17, 5),
            new Review($profile17, $profile16, 4, 'Norm'),
        ], $manager);
    }
}

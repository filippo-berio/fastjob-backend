<?php

namespace App\DataFixtures;

use App\Core\Entity\Category;
use App\Core\Entity\Profile;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProfileFixtures extends BaseFixtures implements DependentFixtureInterface
{
    const PROFILE_1 = 1;
    const PROFILE_2 = 2;
    const PROFILE_3 = 3;
    const PROFILE_4 = 4;
    const PROFILE_5 = 5;

    protected function getEntity(): string
    {
        return Profile::class;
    }

    public function load(ObjectManager $manager)
    {
        $profile2 = new Profile(
            2,
            '88887776655',
            'Алексей',
            'Романов'
        );

        $profile2->setCategories([
            $this->getReference(CategoryFixtures::PLUMBING, Category::class)
        ]);


        $this->save([
            $profile1 = new Profile(
                1,
                '89998887766',
                'Викидий',
                'Алемасов'
            ),
            $profile2,
            $profile3 = new Profile(
                3,
                '87776665544',
                'Шайа',
            ),
            $profile4 = new Profile(
                4,
                '86665554433',
                'Миктор',
                'Штамповский'
            ),
            $profile5 = new Profile(
                5,
                '85554443322',
                'Анатолий',
                'Шляпин'
            ),
        ], $manager);

        $this->addReference(self::PROFILE_1, $profile1);
        $this->addReference(self::PROFILE_2, $profile2);
        $this->addReference(self::PROFILE_3, $profile3);
        $this->addReference(self::PROFILE_4, $profile4);
        $this->addReference(self::PROFILE_5, $profile5);
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class
        ];
    }
}

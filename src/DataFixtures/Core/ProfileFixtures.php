<?php

namespace App\DataFixtures\Core;

use App\Core\Entity\Category;
use App\Core\Entity\Profile;
use App\Core\Entity\User;
use App\DataFixtures\BaseFixtures;
use DateTimeImmutable;
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
            $this->getReference(UserFixtures::USER_2, User::class),
            'Алексей',
            new DateTimeImmutable('14.12.2000')
        );

        $profile2->setCategories([
            $this->getReference(CategoryFixtures::PLUMBING, Category::class)
        ]);

        $profile5 = new Profile(
            $this->getReference(UserFixtures::USER_5, User::class),
            'Анатолий',
            new DateTimeImmutable('18.02.2002')
        );
        $profile5->setCategories([
            $this->getReference(CategoryFixtures::CPLUS, Category::class)
        ]);

        $this->save([
            $profile1 = new Profile(
                $this->getReference(UserFixtures::USER_1, User::class),
                'Викидий',
                new DateTimeImmutable('14.12.2000')
            ),
            $profile2,
            $profile3 = new Profile(
                $this->getReference(UserFixtures::USER_3, User::class),
                'Шайа',
                new DateTimeImmutable('18.02.2002'),
            ),
            $profile4 = new Profile(
                $this->getReference(UserFixtures::USER_4, User::class),
                'Миктор',
                new DateTimeImmutable('14.12.2000')
            ),
            $profile5,
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
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}

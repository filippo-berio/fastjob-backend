<?php

namespace App\DataFixtures\Core;

use App\Auth\Entity\User as AuthUser;
use App\Core\Domain\DTO\Profile\UpdateProfileDTO;
use App\Core\Infrastructure\Entity\Category;
use App\Core\Infrastructure\Entity\Profile;
use App\Core\Domain\Entity\User;
use App\DataFixtures\Auth\UserFixtures;
use App\DataFixtures\BaseFixtures;
use App\DataFixtures\Location\CityFixtures;
use App\Location\Entity\City;
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
    // 6-ой юзер не создал профиль
    const PROFILE_7 = 6;
    const PROFILE_8 = 7;
    const PROFILE_9 = 8;
    const PROFILE_10 = 9;
    const PROFILE_11 = 10;
    const PROFILE_12 = 11;
    const PROFILE_13 = 12;
    const PROFILE_14 = 13;
    const PROFILE_15 = 14;

    protected function getEntity(): string
    {
        return Profile::class;
    }

    public function load(ObjectManager $manager)
    {
        $profile1 = new Profile(
            $this->getCoreUser(UserFixtures::USER_1),
            'Викидий',
            new DateTimeImmutable('14.12.2000'),
        );

        $profile1->update(new UpdateProfileDTO(
            'Викидий',
                [
                    $this->getReference(CategoryFixtures::PLUMBING, Category::class)
                ],
            description: 'Описание',
            city: $this->getReference(CityFixtures::CITY_1, City::class))
        );

        $profile5 = new Profile(
            $this->getCoreUser(UserFixtures::USER_5),
            'Анатолий',
            new DateTimeImmutable('18.02.2002')
        );

        $profile5->update(new UpdateProfileDTO(
            'Анатолий',
            [
                $this->getReference(CategoryFixtures::CPLUS, Category::class),
                $this->getReference(CategoryFixtures::CODING, Category::class),
            ],
        ));

        $profile7 = new Profile(
            $this->getCoreUser(UserFixtures::USER_7),
            'Ярослав',
            new DateTimeImmutable('18.02.2000'),
        );
        $profile7->update(new UpdateProfileDTO(
            'Ярослав',
            [
                $this->getReference(CategoryFixtures::PETS, Category::class),
            ],
            'Великий',
            'Love animals',
            $this->getReference(CityFixtures::CITY_1, City::class),
        ));

        $this->save([
            $profile1,
            $profile2 = new Profile(
                $this->getCoreUser(UserFixtures::USER_2),
                'Алексей',
                new DateTimeImmutable('14.12.2000')
            ),
            $profile3 = new Profile(
                $this->getCoreUser(UserFixtures::USER_3),
                'Шайа',
                new DateTimeImmutable('18.02.2002'),
            ),
            $profile4 = new Profile(
                $this->getCoreUser(UserFixtures::USER_4),
                'Миктор',
                new DateTimeImmutable('14.12.2000')
            ),
            $profile5,
            $profile7,
            $profile8 = new Profile(
                $this->getCoreUser(UserFixtures::USER_8),
                'Фекалий',
                new DateTimeImmutable('24.01.1935'),
            ),
            $profile9 = new Profile(
                $this->getCoreUser(UserFixtures::USER_9),
                'Аромий',
                new DateTimeImmutable('25.04.2000'),
            ),
            $profile10 = new Profile(
                $this->getCoreUser(UserFixtures::USER_10),
                'Ганжубий',
                new DateTimeImmutable('10.10.2000'),
            ),
            $profile11 = new Profile(
                $this->getCoreUser(UserFixtures::USER_11),
                'Ворожей',
                new DateTimeImmutable('10.10.2000'),
            ),
            $profile12 = new Profile(
                $this->getCoreUser(UserFixtures::USER_12),
                'Антуан',
                new DateTimeImmutable('10.10.2000'),
            ),
            $profile13 = new Profile(
                $this->getCoreUser(UserFixtures::USER_13),
                'Горилло',
                new DateTimeImmutable('10.10.2000'),
            ),
            $profile14 = new Profile(
                $this->getCoreUser(UserFixtures::USER_14),
                'Филип',
                new DateTimeImmutable('10.10.2000'),
            ),
            $profile15 = new Profile(
                $this->getCoreUser(UserFixtures::USER_15),
                'Филлимон',
                new DateTimeImmutable('10.10.2000'),
            ),
        ], $manager);

        $this->addReference(self::PROFILE_1, $profile1);
        $this->addReference(self::PROFILE_2, $profile2);
        $this->addReference(self::PROFILE_3, $profile3);
        $this->addReference(self::PROFILE_4, $profile4);
        $this->addReference(self::PROFILE_5, $profile5);
        $this->addReference(self::PROFILE_7, $profile7);
        $this->addReference(self::PROFILE_8, $profile8);
        $this->addReference(self::PROFILE_9, $profile9);
        $this->addReference(self::PROFILE_10, $profile10);
        $this->addReference(self::PROFILE_11, $profile11);
        $this->addReference(self::PROFILE_12, $profile12);
        $this->addReference(self::PROFILE_13, $profile13);
        $this->addReference(self::PROFILE_14, $profile14);
        $this->addReference(self::PROFILE_15, $profile15);
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
            CityFixtures::class,
        ];
    }

    private function getCoreUser(int $id): User
    {
        $authUser = $this->getAuthUser($id);
        return new User($authUser->getId(), $authUser->getPhone());
    }

    private function getAuthUser(int $id): AuthUser
    {
        return $this->getReference($id, AuthUser::class);
    }
}

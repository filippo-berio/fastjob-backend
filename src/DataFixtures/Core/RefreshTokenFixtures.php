<?php

namespace App\DataFixtures\Core;

use App\Auth\Entity\RefreshToken;
use App\Auth\Entity\User;
use App\DataFixtures\BaseFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RefreshTokenFixtures extends BaseFixtures implements DependentFixtureInterface
{
    const REFRESH_TOKEN_1 = 'refresh-token-1';
    const REFRESH_TOKEN_2 = 'refresh-token-2';
    const REFRESH_TOKEN_3 = 'refresh-token-3';
    const REFRESH_TOKEN_4 = 'refresh-token-4';
    const REFRESH_TOKEN_5 = 'refresh-token-5';

    protected function getEntity(): string
    {
        return RefreshToken::class;
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $this->save([
            new RefreshToken(
                $this->getReference(UserFixtures::USER_1, User::class),
                self::REFRESH_TOKEN_1,
            ),
            new RefreshToken(
                $this->getReference(UserFixtures::USER_2, User::class),
                self::REFRESH_TOKEN_2,
            ),
            new RefreshToken(
                $this->getReference(UserFixtures::USER_3, User::class),
                self::REFRESH_TOKEN_3,
            ),
            new RefreshToken(
                $this->getReference(UserFixtures::USER_4, User::class),
                self::REFRESH_TOKEN_4,
            ),
            new RefreshToken(
                $this->getReference(UserFixtures::USER_5, User::class),
                self::REFRESH_TOKEN_5,
            ),
        ], $manager);
    }
}

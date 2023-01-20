<?php

namespace App\DataFixtures\Core;

use App\Core\Entity\User;
use App\DataFixtures\BaseFixtures;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends BaseFixtures
{
    const USER_1_PHONE = '+79111111111';
    const USER_2_PHONE = '+79222222222';
    const USER_3_PHONE = '+79333333333';
    const USER_4_PHONE = '+79444444444';
    const USER_5_PHONE = '+79555555555';
    const USER_6_PHONE = '+79666666666';
    const NOT_EXIST_USER_PHONE = '+79879543210';

    const USER_1 = 1;
    const USER_2 = 2;
    const USER_3 = 3;
    const USER_4 = 4;
    const USER_5 = 5;
    const USER_6 = 6;


    protected function getEntity(): string
    {
        return User::class;
    }

    public function load(ObjectManager $manager)
    {
        $this->save([
            $user1 = new User(self::USER_1_PHONE),
            $user2 = new User(self::USER_2_PHONE),
            $user3 = new User(self::USER_3_PHONE),
            $user4 = new User(self::USER_4_PHONE),
            $user5 = new User(self::USER_5_PHONE),
            $user6 = new User(self::USER_6_PHONE),
        ], $manager);

        $this->addReference(self::USER_1, $user1);
        $this->addReference(self::USER_2, $user2);
        $this->addReference(self::USER_3, $user3);
        $this->addReference(self::USER_4, $user4);
        $this->addReference(self::USER_5, $user5);
        $this->addReference(self::USER_6, $user6);
    }
}

<?php

namespace App\DataFixtures\Auth;

use App\Auth\Entity\User;
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
    const USER_7_PHONE = '+79777777777';
    const USER_8_PHONE = '+79888888888';
    const USER_9_PHONE = '+79999999999';
    const USER_10_PHONE = '+79111111110';
    const USER_11_PHONE = '+79222222211';
    const USER_12_PHONE = '+79111111113';
    const NOT_EXIST_USER_PHONE = '+79879543210';

    const USER_1 = 1;
    const USER_2 = 2;
    const USER_3 = 3;
    const USER_4 = 4;
    const USER_5 = 5;
    const USER_6 = 6;
    const USER_7 = 7;
    const USER_8 = 8;
    const USER_9 = 9;
    const USER_10 = 10;
    const USER_11 = 11;
    const USER_12 = 12;


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
            $user7 = new User(self::USER_7_PHONE),
            $user8 = new User(self::USER_8_PHONE),
            $user9 = new User(self::USER_9_PHONE),
            $user10 = new User(self::USER_10_PHONE),
            $user11 = new User(self::USER_11_PHONE),
            $user12 = new User(self::USER_12_PHONE),
        ], $manager);

        $this->addReference(self::USER_1, $user1);
        $this->addReference(self::USER_2, $user2);
        $this->addReference(self::USER_3, $user3);
        $this->addReference(self::USER_4, $user4);
        $this->addReference(self::USER_5, $user5);
        $this->addReference(self::USER_6, $user6);
        $this->addReference(self::USER_7, $user7);
        $this->addReference(self::USER_8, $user8);
        $this->addReference(self::USER_9, $user9);
        $this->addReference(self::USER_10, $user10);
        $this->addReference(self::USER_11, $user11);
        $this->addReference(self::USER_12, $user12);
    }
}

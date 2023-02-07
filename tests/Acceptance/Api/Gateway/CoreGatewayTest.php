<?php

namespace App\Tests\Acceptance\Api\Gateway;

use App\Api\Gateway\CoreGateway;
use App\Auth\Entity\User;
use App\DataFixtures\Auth\UserFixtures;
use App\DataFixtures\Core\ProfileFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class CoreGatewayTest extends AcceptanceTest
{

    /** @dataProvider data */
    public function testSuccess(int $userId, ?int $profileId)
    {
        $user = $this->getEntity(User::class, $userId);
        $profile = $this->getDependency(CoreGateway::class)->findProfileByAuthUser($user);
        $this->assertEquals($profileId, $profile?->getId());
    }

    private function data()
    {
        return [
            [
                UserFixtures::USER_1,
                ProfileFixtures::PROFILE_1,
            ],
            [
                UserFixtures::USER_6,
                null,
            ],
        ];
    }
}

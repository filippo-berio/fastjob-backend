<?php

namespace App\Tests\Application\Controller\ProfileController;

use App\Auth\Entity\User;
use App\DataFixtures\Auth\UserFixtures;
use App\Tests\Application\ApplicationTest;

class ProfileControllerCreateTest extends ApplicationTest
{
    /**
     * @dataProvider validData
     */
    public function testNotAuthorized(int $userId, array $data)
    {
        $client = $this->createClient();
        $this->notAuthorizedTest($client, 'POST', '/api/profile/create', $data);
    }

    /**
     * @dataProvider validData
     */
    public function testSuccess(int $userId, array $data)
    {
        $client = $this->createClient();
        $this->setUser($client, $userId, User::class);
        $client->request('POST', '/api/profile/create', $data);
        $this->assertResponseStatusCodeSame(200);
    }

    private function validData()
    {
        return [
            [
                UserFixtures::USER_6,
                [
                    'firstName' => 'Имя',
                    'birthDate' => '2000-12-14',
                ],
            ]
        ];
    }
}

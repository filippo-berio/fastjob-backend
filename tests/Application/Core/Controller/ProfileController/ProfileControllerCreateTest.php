<?php

namespace App\Tests\Application\Core\Controller\ProfileController;

use App\DataFixtures\Core\UserFixtures;
use App\Tests\Application\ApplicationTest;

class ProfileControllerCreateTest extends ApplicationTest
{
    /**
     * @dataProvider validData
     */
    public function testNotAuthorized(int $userId, array $data)
    {
        $client = $this->createClient();
        $this->notAuthorizedTest($client, 'POST', '/api/profile', $data);
    }

    /**
     * @dataProvider validData
     */
    public function testSuccess(int $userId, array $data)
    {
        $client = $this->createClient();
        $this->setUser($client, $userId);
        $client->request('POST', '/api/profile', $data);
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

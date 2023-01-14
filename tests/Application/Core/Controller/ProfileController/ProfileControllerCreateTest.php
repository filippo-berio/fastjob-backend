<?php

namespace App\Tests\Application\Core\Controller\ProfileController;

use App\DataFixtures\Core\UserFixtures;
use App\Tests\Application\ApplicationTest;

class ProfileControllerCreateTest extends ApplicationTest
{
    public function testNotAuthorized()
    {
        $client = $this->createClient();
        $this->notAuthorizedTest($client, 'POST', '/api/profile', [
            'firstName' => 'Имя',
            'birthDate' => '2000-14-12'
        ]);
    }

    /**
     * @dataProvider validData
     */
    public function testSuccess(int $userId, string $firstName, string $birthDate)
    {
        $client = $this->createClient();
        $this->setUser($client, $userId);
        $client->request('POST', '/api/profile', [
            'firstName' => $firstName,
            'birthDate' => $birthDate,
        ]);
        $this->assertResponseStatusCodeSame(200);
    }

    private function validData()
    {
        return [
            [
                UserFixtures::USER_6,
                'Имя',
                '2000-12-14'
            ]
        ];
    }
}

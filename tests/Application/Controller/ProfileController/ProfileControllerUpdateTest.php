<?php

namespace App\Tests\Application\Controller\ProfileController;

use App\Core\Entity\Profile;
use App\DataFixtures\Auth\UserFixtures;
use App\Tests\Application\ApplicationTest;

class ProfileControllerUpdateTest extends ApplicationTest
{
    /**
     * @dataProvider validData
     */
    public function testNotAuthorized(int $userId, array $data)
    {
        $client = $this->createClient();
        $this->notAuthorizedTest($client, 'PUT', '/api/profile', $data);
    }

    /**
     * @dataProvider validData
     */
    public function testSuccess(int $userId, array $data)
    {
        $client = $this->createClient();
        $this->setUser($client, $userId, Profile::class);
        $client->request('PUT', '/api/profile', $data);
        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @dataProvider invalidData
     */
    public function testError(int $userId, array $data, int $code)
    {
        $client = $this->createClient();
        $this->setUser($client, $userId, Profile::class);
        $client->request('PUT', '/api/profile', $data);
        $this->assertResponseStatusCodeSame($code);
    }

    private function invalidData()
    {
        return [
            [
                UserFixtures::USER_1,
                [
                    'lastName' => 'Фамилия',
                ],
                400,
            ],
//            [
//                UserFixtures::USER_1,
//                [
//                    'firstName' => 'Имя',
//                    'cityId' => 999,
//                ],
//                404,
//            ],
            [
                UserFixtures::USER_1,
                [
                    'firstName' => 'Имя',
                    'cityId' => 'string',
                ],
                400,
            ],
//            [
//                UserFixtures::USER_6,
//                [
//                    'firstName' => 'Имя',
//                ],
//                404,
//            ]
        ];
    }

    private function validData()
    {
        return [
            [
                UserFixtures::USER_1,
                [
                    'firstName' => 'Имя',
                ]
            ],
            [
                UserFixtures::USER_1,
                [
                    'firstName' => 'Имя',
                    'lastName' => 'Фамилия',
                ]
            ],
            [
                UserFixtures::USER_1,
                [
                    'firstName' => 'Имя',
                    'description' => 'Описание',
                ]
            ],
            [
                UserFixtures::USER_1,
                [
                    'firstName' => 'Имя',
                    'lastName' => 'Фамилия',
                    'description' => 'Описание',
                ]
            ],
        ];
    }
}

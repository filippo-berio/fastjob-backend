<?php

namespace App\Tests\Application\Controller\TaskController;

use App\Core\Entity\Profile;
use App\DataFixtures\Core\CategoryFixtures;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Location\CityFixtures;
use App\Tests\Application\ApplicationTest;
use DateTimeImmutable;

class TaskControllerCreateTest extends ApplicationTest
{
    /**
     * @dataProvider validData
     */
    public function testSuccess(int $userId, array $data)
    {
        $client = $this->createClient();
        $this->setUser($client, $userId, Profile::class);
        $client->request('POST', '/api/task', $data);
        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @dataProvider validData
     */
    public function testNoProfile(int $userId, array $data)
    {
        $client = $this->createClient();
        $this->notAuthorizedTest($client, 'POST', '/api/task', $data);
    }

    /**
     * @dataProvider validData
     */
    public function testNotAuthorized(int $userId, array $data)
    {
        $client = $this->createClient();
        $this->notAuthorizedTest($client, 'POST', '/api/task', $data);
    }

    /**
     * @dataProvider invalidData
     */
    public function testError(int $userId, array $data)
    {
        $client = $this->createClient();
        $this->setUser($client, $userId, Profile::class);
        $client->request('POST', '/api/task', $data);
        $this->assertResponseStatusCodeSame(400);
    }

    private function invalidData()
    {
        return [
            [
                ProfileFixtures::PROFILE_1,
                [
                    'categoryIds' => [
                        CategoryFixtures::COMPUTER_REPAIR,
                    ],
                ],
            ],
            [
                ProfileFixtures::PROFILE_1,
                [
                    'title' => 'title',
                    'categoryIds' => [
                        CategoryFixtures::COMPUTER_REPAIR,
                    ],
                    'address' => [
                        'cityId' => 'CityFixtures::CITY_1',
                        'title' => 'address'
                    ],
                    'deadline' => (new DateTimeImmutable())->modify('+1 day')->format('Y-m-d')
                ]
            ],
            [
                ProfileFixtures::PROFILE_1,
                [
                    'title' => 'title',
                    'categoryIds' => [
                        CategoryFixtures::COMPUTER_REPAIR,
                    ],
                    'address' => [
                        'title' => 'address'
                    ],
                    'deadline' => (new DateTimeImmutable())->modify('+1 day')->format('Y-m-d')
                ]
            ],
            [
                ProfileFixtures::PROFILE_1,
                [
                    'title' => 'title',
                    'categoryIds' => [
                        CategoryFixtures::COMPUTER_REPAIR,
                    ],
                    'address' => [
                        'cityId' => CityFixtures::CITY_1,
                    ],
                ]
            ],
            [
                ProfileFixtures::PROFILE_1,
                [
                    'title' => 'title',
                    'deadline' => 'string'
                ]
            ],
        ];
    }

    private function validData()
    {
        return [
            [
                ProfileFixtures::PROFILE_1,
                [
                    'title' => 'Title'
                ],
            ],
            [
                ProfileFixtures::PROFILE_1,
                [
                    'title' => 'title',
                    'categoryIds' => [
                        CategoryFixtures::COMPUTER_REPAIR,
                    ],
                    'address' => [
                        'cityId' => CityFixtures::CITY_1,
                        'title' => 'address'
                    ],
                    'deadline' => (new DateTimeImmutable())->modify('+1 day')->format('Y-m-d')
                ]
            ],
        ];
    }

}

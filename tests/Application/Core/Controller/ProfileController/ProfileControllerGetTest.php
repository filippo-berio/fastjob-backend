<?php

namespace App\Tests\Application\Core\Controller\ProfileController;

use App\DataFixtures\Core\CategoryFixtures;
use App\DataFixtures\Core\UserFixtures;
use App\Tests\Application\ApplicationTest;

class ProfileControllerGetTest extends ApplicationTest
{
    public function testNotAuthorized()
    {
        $client = $this->createClient();
        $this->notAuthorizedTest($client, 'GET', '/api/profile');
    }

    public function testStructure()
    {
        $client = $this->createClient();
        $this->setUser($client, UserFixtures::USER_1);
        $client->request('GET', '/api/profile');
        $profile = $this->getResponse($client);
        $this->assertEquals('Викидий', $profile['firstName']);
        $this->assertEquals(UserFixtures::USER_1_PHONE, $profile['phone']);
        $this->assertEquals('2000-12-14', $profile['birthDate']);
        $this->assertNull($profile['lastName']);
        $this->assertEquals('Описание', $profile['description']);
        // TODO
        $this->assertNull($profile['photoPath']);
        $this->assertEquals(CategoryFixtures::PLUMBING, $profile['categories'][0]['id']);
    }

    public function testNoProfile()
    {
        $client = $this->createClient();
        $this->setUser($client, UserFixtures::USER_6);
        $client->request('GET', '/api/profile');
        // TODO
//        $this->assertResponseStatusCodeSame(404);
    }
}

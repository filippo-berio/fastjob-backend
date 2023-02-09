<?php

namespace App\Tests\Web\Core\Profile;

use App\DataFixtures\Auth\RefreshTokenFixtures;
use App\DataFixtures\Auth\UserFixtures;
use App\Tests\Web\WebTest;

class ProfileGetWebTest extends WebTest
{
    public function testEmpty()
    {
        $this->assertFalse(false);
    }
//    public function testProfileReturns()
//    {
//        $this->setUserToken(UserFixtures::USER_1);
//        $this->client->request('GET', '/api/profile', [
//            'headers' => $this->getAuthHeaders(UserFixtures::USER_1, RefreshTokenFixtures::REFRESH_TOKEN_1)
//        ]);
//        $response = json_decode($this->client->getResponse()->getContent(), true);
//    }
}

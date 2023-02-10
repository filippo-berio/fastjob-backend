<?php

namespace App\Tests\Web\Core\Profile\ProfileController;

use App\DataFixtures\Auth\RefreshTokenFixtures;
use App\DataFixtures\Auth\UserFixtures;
use App\DataFixtures\Core\ProfileFixtures;
use App\Tests\Web\WebTest;

class ProfileGetWebTest extends WebTest
{
    public function testProfileReturns()
    {
        $this->setUserToken(UserFixtures::USER_1);
        $this->client->request(
            'GET',
            '/api/profile',
            server: $this->getAuthHeaders(UserFixtures::USER_1, RefreshTokenFixtures::REFRESH_TOKEN_1)
        );
        $this->assertResponseCode(200);
        $response = $this->getJsonResponse();
        $this->assertEquals(true, $response['success']);
        $this->assertEquals(ProfileFixtures::PROFILE_1, $response['data']['id']);
    }
}

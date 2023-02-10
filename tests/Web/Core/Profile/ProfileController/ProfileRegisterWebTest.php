<?php

namespace App\Tests\Web\Core\Profile\ProfileController;

use App\DataFixtures\Auth\RefreshTokenFixtures;
use App\DataFixtures\Auth\UserFixtures;
use App\Tests\Web\WebTest;

class ProfileRegisterWebTest extends WebTest
{
    public function testProfileRegister()
    {
        $this->setUserToken(UserFixtures::USER_1);
        $this->client->request(
            'POST',
            '/api/profile/register',
            [
                'firstName' => 'FirstName',
                'birthDate' => '2002-02-18'
            ],
            server: $this->getAuthHeaders(UserFixtures::USER_6, RefreshTokenFixtures::REFRESH_TOKEN_6)
        );
        $this->assertResponseCode(200);
        $this->assertTrue($this->getJsonResponse()['success']);
    }
}

<?php

namespace App\Tests\Unit\Authistic\Adapter;

use App\Authistic\DTO\TokenPair;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\Response\MockResponse;

class AuthisticAdapterLoginTest extends KernelTestCase
{
    use AuthisticAdapterTestTrait;

    public function testLogin()
    {
        $loginResponse = new MockResponse(json_encode([
            'accessToken' => 'access-token',
            'refreshToken' => 'refresh-token',
        ]));

        $adapter = $this->createAdapter([$loginResponse]);

        $tokenPair = $adapter->login('$login', '$password');

        $this->assertRequest(
            $loginResponse,
            'POST',
            '/login',
            expectedBody: [
                'login' => '$login',
                'password' => '$password'
            ]
        );

        $this->assertInstanceOf(TokenPair::class, $tokenPair);
        $this->assertEquals('access-token', $tokenPair->getAccessToken());
        $this->assertEquals('refresh-token', $tokenPair->getRefreshToken());
    }

    protected function getInstance(): KernelTestCase
    {
        return $this;
    }
}

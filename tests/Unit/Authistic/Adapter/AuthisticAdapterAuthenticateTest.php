<?php

namespace App\Tests\Unit\Authistic\Adapter;

use App\Authistic\DTO\TokenPair;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\Response\MockResponse;

class AuthisticAdapterAuthenticateTest extends KernelTestCase
{
    use AuthisticAdapterTestTrait;

    public function testLogin()
    {
        $authenticateResponse = new MockResponse(json_encode([
            'accessToken' => 'new-access-token',
            'refreshToken' => 'new-refresh-token',
        ]));

        $adapter = $this->createAdapter([$authenticateResponse]);

        $tokenPair = $adapter->authenticate('access-token', 'refresh-token');

        $this->assertRequest(
            $authenticateResponse,
            'POST',
            '/authenticate',
            expectedBody: [
                'accessToken' => 'access-token',
                'refreshToken' => 'refresh-token'
            ]
        );

        $this->assertInstanceOf(TokenPair::class, $tokenPair);
        $this->assertEquals('new-access-token', $tokenPair->getAccessToken());
        $this->assertEquals('new-refresh-token', $tokenPair->getRefreshToken());
    }

    protected function getInstance(): KernelTestCase
    {
        return $this;
    }
}

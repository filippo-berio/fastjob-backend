<?php

namespace App\Tests\Web;

use App\Tests\Web\Core\KernelTestWrapper;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class WebTest extends WebTestCase
{
    protected KernelTestWrapper $kernelWrapper;
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->kernelWrapper = new KernelTestWrapper();
        $this->kernelWrapper->setUp();
    }

    protected function tearDown(): void
    {
        $this->kernelWrapper->tearDown();
    }

    protected function setUserToken(int $userId)
    {
        $this->kernelWrapper->kernelSetInRedis("access-token:$userId", $this->makeAccessToken($userId));
    }

    protected function getAuthHeaders(int $userId, ?string $refreshToken = null)
    {
        return [
            'HTTP_Authorization' => 'Bearer ' . $this->makeAccessToken($userId),
            'HTTP_x-refresh-token' => $refreshToken
        ];
    }

    protected function assertResponseCode(int $expected)
    {
        $this->assertEquals($expected, $this->client->getResponse()->getStatusCode());
    }

    protected function getResponse()
    {
        return $this->client->getResponse()->getContent();
    }

    protected function getJsonResponse()
    {
        return json_decode($this->getResponse(), true);
    }

    private function makeAccessToken(int $userId)
    {
        return '_.' . base64_encode(json_encode([
            'userId' => $userId
        ])) . '._';
    }
}

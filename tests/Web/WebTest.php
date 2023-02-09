<?php

namespace App\Tests\Web;

use App\Tests\Acceptance\AcceptanceTest;
use App\Tests\Web\Core\KernelTestWrapper;
use Predis\Client;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class WebTest extends WebTestCase
{
    protected KernelTestWrapper $kernelWrapper;
    protected KernelBrowser $client;
    protected Client $redis;

    protected function setUp(): void
    {
        $this->kernelWrapper = new KernelTestWrapper();
        $this->client = static::createClient();
        $this->redis = new Client(AcceptanceTest::REDIS_HOST);
    }

    protected function tearDown(): void
    {
        $this->kernelWrapper->tearDown();
    }

    protected function setUserToken(int $userId)
    {
        $this->redis->set("access-token:$userId", $this->makeAccessToken($userId));
    }

    protected function getAuthHeaders(int $userId, ?string $refreshToken = null)
    {
        return [
            'Authorization' => 'Bearer ' . $this->makeAccessToken($userId),
            'x-refresh-token' => $refreshToken
        ];
    }

    private function makeAccessToken(int $userId)
    {
        return '_.' . base64_encode(json_encode([
            'userId' => $userId
        ])) . '._';
    }
}

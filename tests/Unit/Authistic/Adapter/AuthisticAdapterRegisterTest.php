<?php

namespace App\Tests\Unit\Authistic\Adapter;

use App\Authistic\Exception\RegisterException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\Response\MockResponse;

class AuthisticAdapterRegisterTest extends KernelTestCase
{
    use AuthisticAdapterTestTrait;

    public function testRegister()
    {
        $registerResponse = new MockResponse(json_encode([
            'success' => true
        ]));

        $adapter = $this->createAdapter([$registerResponse]);
        $adapter->register('user', 'password');

        $this->assertRequest(
            $registerResponse,
            'POST',
            '/register',
            expectedBody: [
                'login' => 'user',
                'password' => 'password'
            ]
        );
    }

    public function testError()
    {
        $registerResponse = new MockResponse(info: [
            'http_code' => 403
        ]);
        $adapter = $this->createAdapter([$registerResponse]);
        $this->expectException(RegisterException::class);
        $adapter->register('user', 'password');
    }

    protected function getInstance(): KernelTestCase
    {
        return $this;
    }
}

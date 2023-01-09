<?php

namespace App\Tests\Unit\Authistic\Adapter;

use App\Authistic\Adapter\AuthisticAdapter;
use App\Authistic\Adapter\AuthisticAdapterInterface;
use App\Authistic\Adapter\AuthisticClient;
use App\Http\Client\ClientBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

trait AuthisticAdapterTestTrait
{
    /**
     * @param MockResponse[] $mockResponses
     * @return AuthisticAdapterInterface
     */
    protected function createAdapter(array $mockResponses): AuthisticAdapterInterface
    {
        $symfonyHttpClient = new MockHttpClient($mockResponses);

        $authClient = new AuthisticClient(
            new ClientBuilder($symfonyHttpClient),
            'http://authistic-host',
            'authistic-key'
        );

        return new AuthisticAdapter($authClient);
    }

    protected function assertRequest(
        MockResponse   $response,
        string $expectedMethod,
        string $expectedUri,
        ?array $expectedQuery = null,
        ?array $expectedBody = null,
    ) {
        $this->getInstance()->assertEquals($expectedMethod, $response->getRequestMethod());
        $this->getInstance()->assertEquals(200, $response->getStatusCode());
        $this->getInstance()->assertEquals('http://authistic-host' . $expectedUri, $response->getRequestUrl());
        $this->getInstance()->assertContains('Authorization: Bearer authistic-key', $response->getRequestOptions()['headers']);

        if ($expectedBody) {
            $this->getInstance()->assertEquals(json_encode($expectedBody, 256), $response->getRequestOptions()['body']);
        }
        if ($expectedQuery) {
            $this->getInstance()->assertEquals($expectedQuery, $response->getRequestOptions()['query']);
        }
    }

    protected abstract function getInstance(): KernelTestCase;
}

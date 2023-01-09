<?php

namespace App\Http\Client;

use App\Http\Method\MethodInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface as SymfonyHttpClientInterface;

class Client implements ClientInterface
{
    public function __construct(
        private SymfonyHttpClientInterface $httpClient,
    ) {
    }

    /**
     * @throws HttpExceptionInterface
     */
    public function request(MethodInterface $method): array
    {
        $response = $this->httpClient->request(
            $method->getHttpMethod(),
            $method->getUri(),
            [
                'json' => $method->buildJson(),
                'query' => $method->buildQuery()
            ]
        );
        return json_decode($response->getContent(), true);
    }
}

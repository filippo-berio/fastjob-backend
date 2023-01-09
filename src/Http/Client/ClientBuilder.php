<?php

namespace App\Http\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface as SymfonyClientInterface;

class ClientBuilder
{
    public function __construct(
        private SymfonyClientInterface $httpClient
    ) {
    }

    public function build(string $host, array $options = []): ClientInterface
    {
        return new Client($this->httpClient->withOptions([
            'base_uri' => $host,
            ...$options
        ]));
    }
}

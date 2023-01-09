<?php

namespace App\Authistic\Adapter;

use App\Authistic\Exception\AuthisticException;
use App\Http\Client\ClientBuilder;
use App\Http\Client\ClientInterface;
use App\Http\Method\MethodInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Throwable;

class AuthisticClient implements ClientInterface
{
    private ClientInterface $client;

    public function __construct(
        ClientBuilder $clientBuilder,
        string        $host,
        string        $key,
    ) {
        $this->client = $clientBuilder->build($host, [
            'auth_bearer' => $key
        ]);
    }

    public function request(MethodInterface $method)
    {
        try {
            return $this->client->request($method);
        } catch (HttpExceptionInterface $e) {
            $response = $e->getResponse();
            $message = json_decode($response->getContent(false), true)['message'];
            throw new AuthisticException($message, $response->getStatusCode(), $e);
        }
    }
}

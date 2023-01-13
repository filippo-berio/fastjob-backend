<?php

namespace App\Http\Client;

use App\Http\Exception\HttpException;
use App\Http\Method\MethodInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface as SymfonyHttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

class JsonClient implements ClientInterface
{
    public function __construct(
        private SymfonyHttpClientInterface $httpClient,
    ) {
    }

    public function request(MethodInterface $method): array
    {
        try {
            $response = $this->httpClient->request(
                $method->getHttpMethod(),
                $method->getUri(),
                [
                    'json' => $method->buildJson(),
                    'query' => $method->buildQuery()
                ]
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode > 299 || $statusCode < 200) {
                throw $this->buildException($response);
            }
        } catch (HttpExceptionInterface $exception) {
            $response = $exception->getResponse();
            throw $this->buildException($response);
        }
        return json_decode($response->getContent(), true);
    }

    public function buildException(ResponseInterface $response): HttpException
    {
        return new HttpException($response->getContent(), $response->getStatusCode());
    }
}

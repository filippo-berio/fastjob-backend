<?php

namespace App\Lib\Http\Client;

use App\Lib\Http\Exception\HttpException;
use App\Lib\Http\Method\MethodInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface as SymfonyHttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

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
        return $response->getContent() ?
            json_decode($response->getContent(), true)
            : [];
    }

    public function buildException(ResponseInterface $response): HttpException
    {
        return new HttpException($response->getContent(), $response->getStatusCode());
    }
}

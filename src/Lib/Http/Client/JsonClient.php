<?php

namespace App\Lib\Http\Client;

use App\Lib\Http\Exception\HttpException;
use App\Lib\Http\Method\MethodInterface;
use Symfony\Component\HttpClient\HttpOptions;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpExceptionInterface;
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
                $this->buildOptions($method)->toArray(),
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode > 400 || $statusCode < 200) {
                throw $this->buildException($response->getContent(), $statusCode);
            }
        } catch (HttpExceptionInterface $exception) {
            throw $this->buildException($exception->getMessage());
        }
        return $response->getContent() ?
            json_decode($response->getContent(), true)
            : [];
    }

    public function buildException(string $message, int $statusCode = 500): HttpException
    {
        return new HttpException($message, $statusCode);
    }

    private function buildOptions(MethodInterface $method): HttpOptions
    {
        $options = new HttpOptions;
        if ($method->buildJson()) {
            $options->setJson($method->buildJson());
        }
        if ($method->buildQuery()) {
            $options->setQuery($method->buildQuery());
        }
        if ($method->buildMultipartData()) {
            $options->setBody($method->buildMultipartData());
        }
        return $options;
    }
}

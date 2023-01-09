<?php

namespace App\Authistic\Adapter;

use App\Authistic\DTO\TokenPair;
use App\Authistic\Exception\RegisterException;
use App\Authistic\Method\AuthenticateMethod;
use App\Authistic\Method\LoginMethod;
use App\Authistic\Method\RegisterMethod;
use Throwable;

class AuthisticAdapter implements AuthisticAdapterInterface
{
    public function __construct(
        private AuthisticClient $client
    ) {
    }

    public function login(string $login, string $password): TokenPair
    {
        $response = $this->client->request(new LoginMethod($login, $password));
        return new TokenPair($response['accessToken'], $response['refreshToken']);
    }

    public function authenticate(string $accessToken, string $refreshToken): TokenPair
    {
        $response = $this->client->request(new AuthenticateMethod($accessToken, $refreshToken));
        return new TokenPair($response['accessToken'], $response['refreshToken']);
    }

    public function register(string $login, string $password)
    {
        try {
            $this->client->request(new RegisterMethod($login, $password));
        } catch (Throwable) {
            throw new RegisterException();
        }
    }
}

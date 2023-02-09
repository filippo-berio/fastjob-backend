<?php

namespace App\Api\Service;

class AccessTokenContext
{
    private string $accessToken;
    private ?string $refreshToken = null;

    public function set(string $accessToken, ?string $refreshToken = null)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken=  $refreshToken;
    }

    public function get(): array
    {
        return [$this->accessToken, $this->refreshToken];
    }

    public function has(): bool
    {
        return isset($this->accessToken);
    }
}

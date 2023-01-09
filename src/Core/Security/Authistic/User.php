<?php

namespace App\Core\Security\Authistic;

use App\Authistic\DTO\TokenPair;
use App\Core\Entity\Profile;
use App\Core\Security\UserInterface;

class User implements UserInterface
{
    private Profile $profile;

    private ?TokenPair $tokenPair;

    public function __construct(
        Profile $profile,
        TokenPair $tokenPair
    ) {
        $this->profile = $profile;
        $this->tokenPair = $tokenPair;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->profile->getPhone();
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function getAccessToken(): string
    {
        return $this->tokenPair->getAccessToken();
    }

    public function getRefreshToken(): string
    {
        return $this->tokenPair->getRefreshToken();
    }
}

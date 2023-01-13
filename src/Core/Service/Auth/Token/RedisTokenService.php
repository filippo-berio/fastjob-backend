<?php

namespace App\Core\Service\Auth\Token;

use App\Core\Entity\User;
use Predis\Client;

class RedisTokenService
{
    public function __construct(
        private Client $redis,
        private int $accessTokenLifeTime,
    ) {
    }

    public function setConfirmationCode(string $value, string $phone)
    {
        $phone = str_replace('+', '', $phone);
        $this->redis->set('confirm-token-' . $phone, $value, 'EX', 60 * 60);
    }

    public function getConfirmationCode(string $phone): ?string
    {
        $phone = str_replace('+', '', $phone);
        return $this->redis->get('confirm-token-' . $phone);
    }

    public function setAccessToken(string $value, User $user)
    {
        $this->redis->set('access-token-' . $user->getId(), $value, 'EX', $this->accessTokenLifeTime);
    }

    public function getAccessToken(User $user): ?string
    {
        return $this->redis->get('access-token-' . $user->getId());
    }
}

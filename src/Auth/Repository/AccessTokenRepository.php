<?php

namespace App\Auth\Repository;

use App\Auth\Entity\AccessToken;
use App\Auth\Entity\User;
use Predis\Client;

class AccessTokenRepository
{
    private Client $redis;

    public function __construct(
        private int $accessTokenLifeTime,
        string $redisHost,
    ) {
        $this->redis = new Client($redisHost);
    }

    public function save(AccessToken $accessToken): AccessToken
    {
        $this->redis->set(
            'access-token:' . $accessToken->getUser()->getId(),
            $accessToken->getValue(),
            'EX',
            $this->accessTokenLifeTime
        );
        return $accessToken;
    }

    public function findByUser(User $user): ?AccessToken
    {
        $value = $this->redis->get('access-token:' . $user->getId());
        if (!$value) {
            return null;
        }
        return new AccessToken($user, $value);
    }
}

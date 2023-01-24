<?php

namespace App\Auth\Repository;

use App\Auth\Entity\ConfirmationToken;
use Predis\Client;

class ConfirmationTokenRepository
{
    private Client $redis;

    public function __construct(
        private int $confirmationTokenLifeTime,
        string $redisHost,
    ) {
        $this->redis = new Client($redisHost);
    }

    public function save(ConfirmationToken $token): ConfirmationToken
    {
        $phone = str_replace('+', '', $token->getPhone());
        $this->redis->set(
            'confirm-token:' . $phone,
            $token->getConfirmationCode() . ':' . $token->getRetries(),
            'EX',
            $this->confirmationTokenLifeTime
        );
        return $token;
    }

    public function findByPhone(string $phone): ?ConfirmationToken
    {
        $phone = str_replace('+', '', $phone);
        $data = $this->redis->get('confirm-token:' . $phone);
        if (!$data) {
            return null;
        }
        $parts = explode(':', $data);
        return new ConfirmationToken(
            $phone,
            $parts[0],
            $parts[1],
        );
    }

    public function delete(string $phone)
    {
        $phone = str_replace('+', '', $phone);
        $this->redis->del('confirm-token:' . $phone);
    }
}

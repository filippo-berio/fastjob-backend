<?php

namespace App\Auth\Repository;

use Predis\Client;

class ConfirmationTimeoutRepository
{
    private Client $redis;

    public function __construct(
        private int $timeout,
        string $redisHost,
    ) {
        $this->redis = new Client($redisHost);
    }

    public function setPhoneTimeout(string $phone)
    {
        $phone = str_replace('+', '', $phone);
        $this->redis->set('sms-timeout:' . $phone, 1, 'EX', $this->timeout);
    }

    public function isPhoneTimeout(string $phone): bool
    {
        $phone = str_replace('+', '', $phone);
        return !!$this->redis->get('sms-timeout:' . $phone);
    }
}

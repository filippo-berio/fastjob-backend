<?php

namespace App\Auth\Repository;

use Predis\Client;

class BannedPhoneRepository
{
    private Client $redis;

    public function __construct(
        private int $phoneBanTime,
        string $redisHost,
    ) {
        $this->redis = new Client($redisHost);
    }

    public function banPhone(string $phone)
    {
        $phone = str_replace('+', '', $phone);
        $this->redis->set('ban-phone:' . $phone, 1, 'EX', $this->phoneBanTime);
    }

    public function isPhoneBanned(string $phone): bool
    {
        $phone = str_replace('+', '', $phone);
        return !!$this->redis->get('ban-phone:' . $phone);
    }
}

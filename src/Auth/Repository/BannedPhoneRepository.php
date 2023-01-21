<?php

namespace App\Auth\Repository;

use App\Auth\Entity\BannedPhone;
use Predis\Client;

class BannedPhoneRepository
{
    public function __construct(
        private Client $redis,
        private int $phoneBanTime,
    ) {
    }

    public function save(BannedPhone $bannedPhone): BannedPhone
    {
        $phone = str_replace('+', '', $bannedPhone->getPhone());
        $this->redis->set('ban-phone-' . $phone, 1, 'EX', $this->phoneBanTime);
        return $bannedPhone;
    }

    public function isPhoneBanned(string $phone): bool
    {
        $phone = str_replace('+', '', $phone);
        return !!$this->redis->get('ban-phone-' . $phone);
    }
}

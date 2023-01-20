<?php

namespace App\Core\Service\Auth\Token;

use App\Core\DTO\Auth\ConfirmationData;
use App\Core\Entity\User;
use Predis\Client;

class RedisTokenService
{
    public function __construct(
        private Client $redis,
        private int $accessTokenLifeTime,
        private int $phoneBanTime,
        private int $confirmationTokenLifeTime,
    ) {
    }

    public function banPhone(string $phone)
    {
        $phone = str_replace('+', '', $phone);
        $this->redis->set('ban-phone-' . $phone, 1, 'EX', $this->phoneBanTime);
    }

    public function isPhoneBanned(string $phone): bool
    {
        $phone = str_replace('+', '', $phone);
        return !!$this->redis->get('ban-phone-' . $phone);
    }

    public function setConfirmationCode(string $value, string $phone)
    {
        $phone = str_replace('+', '', $phone);
        $this->redis->set('confirm-token-' . $phone, $value, 'EX', $this->confirmationTokenLifeTime);
    }

    public function getConfirmationCode(string $phone): ?ConfirmationData
    {
        $phone = str_replace('+', '', $phone);
        $data = $this->redis->get('confirm-token-' . $phone);
        return $data ? ConfirmationData::fromString($data) : null;
    }

    public function deleteConfirmationCode(string $phone)
    {
        $phone = str_replace('+', '', $phone);
        $this->redis->del('confirm-token-' . $phone);
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

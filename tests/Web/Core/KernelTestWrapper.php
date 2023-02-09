<?php

namespace App\Tests\Web\Core;

use App\Auth\Entity\User;
use App\Tests\Acceptance\AcceptanceTest;

class KernelTestWrapper extends AcceptanceTest
{
    public function kernelSetInRedis(string $key, $value)
    {
        $this->redis->set($key, $value);
    }

    public function kernelGetAuthUser(int $id): User
    {
        return $this->getEntity(User::class, $id);
    }
}

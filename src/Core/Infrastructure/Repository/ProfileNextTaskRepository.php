<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Repository\ProfileNextTaskRepositoryInterface;

class ProfileNextTaskRepository extends BaseTaskStackRepository implements ProfileNextTaskRepositoryInterface
{

    protected function getKey(Profile $profile): string
    {
        return 'profile:next-tasks:' . $profile->getId();
    }
}

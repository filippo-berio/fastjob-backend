<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;

interface ProfileNextTaskRepositoryInterface
{
    /**
     * @param Profile $profile
     * @param Task[] $tasks
     */
    public function push(Profile $profile, array $tasks);

    public function popAll(Profile $profile): array;

    public function clear(Profile $profile);
}

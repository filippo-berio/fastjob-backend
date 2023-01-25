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
    public function add(Profile $profile, array $tasks);

    /**
     * @param Profile $profile
     * @return Task[]
     */
    public function get(Profile $profile): array;

    public function pop(Profile $profile): ?Task;

    public function count(Profile $profile): int;
}

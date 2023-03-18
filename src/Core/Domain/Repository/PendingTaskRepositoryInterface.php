<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;

# таски, не свайпнутые исполнителями
interface PendingTaskRepositoryInterface
{
    /**
     * @param Profile $profile
     * @param Task[] $tasks
     */
    public function push(Profile $profile, array $tasks);

    /**
     * @param Profile $profile
     * @return Task[]
     */
    public function get(Profile $profile): array;

    public function pop(Profile $profile): ?Task;
}

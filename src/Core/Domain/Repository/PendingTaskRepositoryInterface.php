<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;

# таски, не свайпнутые исполнителями
interface PendingTaskRepositoryInterface
{
    public function set(Profile $profile, Task $task);

    public function get(Profile $profile): ?Task;

    public function clear(Profile $profile);
}

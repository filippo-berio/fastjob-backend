<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Entity\TaskSwipe;

interface TaskSwipeRepositoryInterface
{
    public function save(TaskSwipe $taskSwipe): TaskSwipe;

    /** @return TaskSwipe[] */
    public function findByProfile(Profile $profile): array;

    public function findByProfileAndTask(Profile $profile, Task $task): ?TaskSwipe;
}

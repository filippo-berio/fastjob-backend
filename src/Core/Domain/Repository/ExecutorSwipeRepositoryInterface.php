<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Entity\ExecutorSwipe;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;

interface ExecutorSwipeRepositoryInterface
{
    public function save(ExecutorSwipe $executorSwipe): ExecutorSwipe;

    public function findByAuthorAndTask(Profile $author, Task $task): ?ExecutorSwipe;
}

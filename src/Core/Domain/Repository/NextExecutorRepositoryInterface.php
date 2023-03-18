<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Entity\Task;
use App\Core\Domain\Entity\TaskSwipe;

interface NextExecutorRepositoryInterface
{
    /**
     * @param Task $task
     * @param int $limit
     * @return TaskSwipe[]
     */
    public function nextSwipedExecutor(Task $task, int $limit): array;
}

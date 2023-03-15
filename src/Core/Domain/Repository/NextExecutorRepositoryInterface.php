<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Entity\NextExecutor;
use App\Core\Domain\Entity\Task;

interface NextExecutorRepositoryInterface
{
    public function nextSwipedExecutor(Task $task): ?NextExecutor;
}

<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\SwipeMatch;
use App\Core\Domain\Entity\Task;

interface SwipeMatchRepositoryInterface
{
    /**
     * @param Task $task
     * @return SwipeMatch[]
     */
    public function findForTask(Task $task): array;

    /**
     * @param Profile $profile
     * @return SwipeMatch[]
     */
    public function findForExecutor(Profile $profile): array;
}

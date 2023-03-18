<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Repository\PendingTaskRepositoryInterface;

# таски, не свайпнутые исполнителями
class PendingTaskRepository extends BaseTaskStackRepository implements PendingTaskRepositoryInterface
{
    protected function getKey(Profile $profile): string
    {
        return 'pending-task:' . $profile->getId();
    }
}

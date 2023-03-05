<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Entity\TaskOffer;

interface TaskOfferRepositoryInterface
{
    /*** @return TaskOffer[] */
    public function findExpired(): array;

    public function save(TaskOffer $taskOffer): TaskOffer;

    public function findByProfileAndTask(Profile $profile, Task $task): ?TaskOffer;

    /*** @return TaskOffer[] */
    public function findWaitOffersForExecutor(Profile $profile): array;
}

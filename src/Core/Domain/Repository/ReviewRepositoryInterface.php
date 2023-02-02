<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Review;
use App\Core\Domain\Entity\Task;

interface ReviewRepositoryInterface
{
    public function save(Review $review): Review;

    /**
     * @param Profile $profile
     * @return Review[]
     */
    public function findForTarget(Profile $profile): array;

    /**
     * @param Profile $executor
     * @return Task[]
     */
    public function getAvailableExecutorReviewTasks(Profile $executor): array;
}

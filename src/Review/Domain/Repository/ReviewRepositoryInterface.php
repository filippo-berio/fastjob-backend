<?php

namespace App\Review\Domain\Repository;

use App\Review\Domain\Entity\Profile;
use App\Review\Domain\Entity\Review;

interface ReviewRepositoryInterface
{
    /**
     * @param Profile $target
     * @return Review[]
     */
    public function findByTarget(Profile $target): array;

    public function save(Review $review): Review;

    public function find(int $id): ?Review;
}

<?php

namespace App\Review\Domain\Service;

use App\Review\Domain\Entity\Profile;
use App\Review\Domain\Entity\Review;
use App\Review\Domain\Repository\ReviewRepositoryInterface;

class GetProfileReviewsService
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository,
    ) {
    }

    /**
     * @param Profile $profile
     * @return Review[]
     */
    public function get(Profile $profile): array
    {
        return $this->reviewRepository->findByTarget($profile);
    }
}

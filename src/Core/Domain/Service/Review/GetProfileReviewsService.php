<?php

namespace App\Core\Domain\Service\Review;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Review;
use App\Core\Domain\Repository\ReviewRepositoryInterface;

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
        return $this->reviewRepository->findForTarget($profile);
    }
}

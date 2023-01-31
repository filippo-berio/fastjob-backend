<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Review;
use App\Core\Domain\Repository\ReviewRepositoryInterface;
use App\Core\Infrastructure\Gateway\ReviewGateway;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function __construct(
        private ReviewGateway $reviewGateway,
    ) {
    }

    public function save(Review $review): Review
    {
        $this->reviewGateway->saveReview($review);
        return $review;
    }

    public function findForProfile(Profile $profile): array
    {
        return $this->reviewGateway->findForTarget($profile);
    }
}

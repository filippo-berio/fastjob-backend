<?php

namespace App\Core\Infrastructure\Gateway;

use App\Core\Domain\Entity\Review;
use App\Core\Domain\Entity\Profile;
use App\Review\Application\UseCase\CreateReviewUseCase;
use App\Review\Application\UseCase\GetProfileReviewsUseCase;
use App\Review\Domain\Entity\Profile as ReviewProfile;
use App\Review\Domain\Entity\Review as ExternalReview;

class ReviewGateway
{
    public function __construct(
        private CreateReviewUseCase $createReviewUseCase,
        private GetProfileReviewsUseCase $getProfileReviewsUseCase,
    ) {
    }

    public function saveReview(Review $review): ExternalReview
    {
        return $this->createReviewUseCase->create(
            new ReviewProfile($review->getAuthor()->getId()),
            $review->getTarget()->getId(),
            $review->getRating(),
            $review->getComment()
        );
    }
    /**
     * @param Profile $profile
     * @return ExternalReview[]
     */
    public function findForTarget(Profile $profile): array
    {
        return $this->getProfileReviewsUseCase->get($profile->getId());
    }
}

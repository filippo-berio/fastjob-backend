<?php

namespace App\Review\Domain\Service;

use App\Review\Domain\Contract\EntityMapperInterface;
use App\Review\Domain\Entity\Profile;
use App\Review\Domain\Entity\Review;
use App\Review\Domain\Exception\Review\ReviewUnavailableException;
use App\Review\Domain\Repository\ReviewAvailabilityRepositoryInterface;
use App\Review\Domain\Repository\ReviewRepositoryInterface;

class CreateReviewService
{
    public function __construct(
        private ReviewAvailabilityRepositoryInterface $reviewAvailabilityRepository,
        private ReviewRepositoryInterface             $reviewRepository,
        private EntityMapperInterface                 $entityMapper,
    ) {
    }

    public function create(
        Profile $author,
        Profile $target,
        int $rating,
        ?string $comment = null
    ): Review {
        $availability = $this->reviewAvailabilityRepository->find($author, $target);
        if (!$availability) {
            throw new ReviewUnavailableException();
        }

        $entity = $this->entityMapper->persistenceEntity(Review::class);
        $review = new $entity(
            $author,
            $target,
            $rating,
            $availability->isAllowComment() ? $comment : null,
        );
        $review = $this->reviewRepository->save($review);
        $this->reviewAvailabilityRepository->delete($availability);
        return $review;
    }
}

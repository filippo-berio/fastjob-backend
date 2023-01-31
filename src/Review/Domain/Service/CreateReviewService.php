<?php

namespace App\Review\Domain\Service;

use App\Review\Domain\Contract\EntityMapperInterface;
use App\Review\Domain\Entity\Profile;
use App\Review\Domain\Entity\Review;
use App\Review\Domain\Repository\ReviewRepositoryInterface;

class CreateReviewService
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository,
        private EntityMapperInterface     $entityMapper,
    ) {
    }

    public function create(
        Profile $author,
        Profile $target,
        int $rating,
        ?string $comment = null
    ): Review {
        $entity = $this->entityMapper->persistenceEntity(Review::class);
        $review = new $entity(
            $author,
            $target,
            $rating,
            $comment,
        );
        $review = $this->reviewRepository->save($review);
        return $review;
    }
}

<?php

namespace App\Core\Domain\Service\Review;

use App\Core\Domain\Contract\EntityMapperInterface;
use App\Core\Domain\DTO\Review\CreateReviewDTO;
use App\Core\Domain\Entity\Review;
use App\Core\Domain\Repository\ReviewRepositoryInterface;

class CreateReviewService
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository,
        private EntityMapperInterface $entityMapper,
    ) {
    }

    public function create(CreateReviewDTO $createReviewDTO): Review
    {
        $entity = $this->entityMapper->persistenceEntity(Review::class);
        $review = new $entity(
            $createReviewDTO->task,
            $createReviewDTO->author,
            $createReviewDTO->target,
            $createReviewDTO->rating,
            $createReviewDTO->comment,
        );
        return $this->reviewRepository->save($review);
    }
}

<?php

namespace App\Review\Application\UseCase;

use App\Review\Domain\Entity\Review;
use App\Review\Domain\Repository\ReviewRepositoryInterface;

class FindReviewByIdUseCase
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository
    ) {
    }

    public function find(int $id): ?Review
    {
        return $this->reviewRepository->find($id);
    }
}

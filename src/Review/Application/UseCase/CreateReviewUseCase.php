<?php

namespace App\Review\Application\UseCase;

use App\Review\Application\Exception\Profile\ProfileNotFoundException;
use App\Review\Domain\Entity\Profile;
use App\Review\Domain\Entity\Review;
use App\Review\Domain\Repository\ProfileRepositoryInterface;
use App\Review\Domain\Service\CreateReviewService;

class CreateReviewUseCase
{
    public function __construct(
        private ProfileRepositoryInterface $profileRepository,
        private CreateReviewService        $createReviewService,
    ) {
    }

    public function create(
        Profile $author,
        int $targetId,
        int $rating,
        ?string $comment = null,
    ): Review {
        if (!$target = $this->profileRepository->find($targetId)) {
            throw new ProfileNotFoundException();
        }
        return $this->createReviewService->create(
            $author,
            $target,
            $rating,
            $comment
        );
    }
}

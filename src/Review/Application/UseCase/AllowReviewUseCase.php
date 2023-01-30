<?php

namespace App\Review\Application\UseCase;

use App\Review\Application\Exception\Profile\ProfileNotFoundException;
use App\Review\Domain\Entity\Profile;
use App\Review\Domain\Repository\ProfileRepositoryInterface;
use App\Review\Domain\Service\AllowReviewService;

class AllowReviewUseCase
{
    public function __construct(
        private ProfileRepositoryInterface $profileRepository,
        private AllowReviewService         $allowReviewService,
    ) {
    }

    public function allow(Profile $author, int $targetId, bool $allowComment)
    {
        if (!$target = $this->profileRepository->find($targetId)) {
            throw new ProfileNotFoundException();
        }
        $this->allowReviewService->allow($author, $target, $allowComment);
    }
}

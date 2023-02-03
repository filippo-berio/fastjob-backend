<?php

namespace App\Core\Application\UseCase\Review;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Service\Review\GetAvailableReviewTargetsService;

class GetAvailableReviewTargetsUseCase
{
    public function __construct(
        private GetAvailableReviewTargetsService $availableReviewTargetsService,
    ) {
    }

    /**
     * @param Profile $profile
     * @return Task[]
     */
    public function get(Profile $profile): array
    {
        return $this->availableReviewTargetsService->get($profile);
    }
}

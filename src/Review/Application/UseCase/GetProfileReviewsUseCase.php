<?php

namespace App\Review\Application\UseCase;

use App\Review\Application\Exception\Profile\ProfileNotFoundException;
use App\Review\Domain\Entity\Review;
use App\Review\Domain\Repository\ProfileRepositoryInterface;
use App\Review\Domain\Service\GetProfileReviewsService;

class GetProfileReviewsUseCase
{
    public function __construct(
        private ProfileRepositoryInterface $profileRepository,
        private GetProfileReviewsService   $getProfileReviewsService,
    ) {
    }

    /**
     * @param string $profileId
     * @return Review[]
     */
    public function get(string $profileId): array
    {
        if (!$profile = $this->profileRepository->find($profileId)) {
            throw new ProfileNotFoundException();
        }
        return $this->getProfileReviewsService->get($profile);
    }
}

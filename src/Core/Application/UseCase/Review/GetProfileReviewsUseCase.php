<?php

namespace App\Core\Application\UseCase\Review;

use App\Core\Domain\Entity\Review;
use App\Core\Domain\Exception\Profile\ProfileNotFoundException;
use App\Core\Domain\Query\Profile\FindProfileById;
use App\Core\Domain\Service\Review\GetProfileReviewsService;
use App\Lib\CQRS\Bus\QueryBusInterface;

class GetProfileReviewsUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private GetProfileReviewsService $getProfileReviewsService,
    ) {
    }

    /**
     * @param int $profileId
     * @return Review[]
     */
    public function get(int $profileId): array
    {
        $profile = $this->queryBus->query(new FindProfileById($profileId));
        if (!$profile) {
            throw new ProfileNotFoundException();
        }
        return $this->getProfileReviewsService->get($profile);
    }
}

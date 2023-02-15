<?php

namespace App\Core\Domain\Service\Review;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Query\Task\FindAvailableReviewTasksForExecutor;
use App\Lib\CQRS\Bus\QueryBusInterface;

class GetAvailableReviewTargetsService
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    /**
     * @param Profile $profile
     * @return Task[]
     */
    public function get(Profile $profile): array
    {
        return $this->queryBus->query(new FindAvailableReviewTasksForExecutor($profile));
    }
}

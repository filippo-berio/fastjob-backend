<?php

namespace App\Core\Domain\Service\Review;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Query\Task\FindTaskByExecutor;
use App\CQRS\Bus\QueryBusInterface;

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
        $executorTasks = $this->queryBus->query(new FindTaskByExecutor($profile));
    }
}

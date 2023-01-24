<?php

namespace App\Core\Service\Task\NextTask\Generator;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Query\Task\FindNextTasksForProfile\FindNextTasksForProfile;
use App\CQRS\Bus\QueryBusInterface;

class CategoryNextTaskGenerator
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    /**
     * @param Profile $profile
     * @param int $count
     * @return Task[]
     */
    public function generateForProfile(Profile $profile, int $count = 1): array
    {
        return $this->queryBus->query(new FindNextTasksForProfile(
            $profile,
            $count
        ));
    }
}

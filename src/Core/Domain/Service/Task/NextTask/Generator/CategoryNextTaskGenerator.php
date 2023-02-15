<?php

namespace App\Core\Domain\Service\Task\NextTask\Generator;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Query\Task\FindNextTasksForProfile;
use App\Lib\CQRS\Bus\QueryBusInterface;

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

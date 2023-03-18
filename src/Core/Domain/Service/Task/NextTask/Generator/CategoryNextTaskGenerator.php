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
        private int $taskStackLimit,
    ) {
    }

    /**
     * @param Profile $profile
     * @return Task[]
     */
    public function generateForProfile(Profile $profile): array
    {
        return $this->queryBus->query(new FindNextTasksForProfile(
            $profile,
            $this->taskStackLimit,
        ));
    }
}

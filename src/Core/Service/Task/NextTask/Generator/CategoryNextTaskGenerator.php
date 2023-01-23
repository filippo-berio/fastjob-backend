<?php

namespace App\Core\Service\Task\NextTask\Generator;

use App\Core\Entity\Profile;
use App\Core\Query\Task\FindNextTasksForProfile\FindNextTasksForProfile;
use App\CQRS\Bus\QueryBusInterface;

class CategoryNextTaskGenerator implements ProfileNextTaskGeneratorInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function generateForProfile(Profile $profile, int $count): array
    {
        return $this->queryBus->query(new FindNextTasksForProfile(
            $profile,
            $count
        ));
    }
}

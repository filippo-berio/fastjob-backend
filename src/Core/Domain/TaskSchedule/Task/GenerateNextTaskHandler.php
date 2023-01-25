<?php

namespace App\Core\Domain\TaskSchedule\Task;

use App\Core\Domain\Query\Profile\FindProfileById;
use App\Core\Domain\Repository\ProfileNextTaskRepositoryInterface;
use App\Core\Domain\Service\Task\NextTask\Generator\CategoryNextTaskGenerator;
use App\CQRS\Bus\QueryBusInterface;

class GenerateNextTaskHandler
{
    public function __construct(
        private CategoryNextTaskGenerator          $nextTaskGenerator,
        private ProfileNextTaskRepositoryInterface $nextTaskRepository,
        private QueryBusInterface                  $queryBus,
    ) {
    }

    public function __invoke(GenerateNextTask $message)
    {
        $profile = $this->queryBus->query(new FindProfileById($message->profileId));
        $tasks = $this->nextTaskGenerator->generateForProfile($profile, $message->count);
        $this->nextTaskRepository->add($profile, $tasks);
    }
}

<?php

namespace App\Core\Domain\Service\Task\NextTask;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Event\EventDispatcherInterface;
use App\Core\Domain\Event\Task\GenerateNext\GenerateNextTaskEvent;
use App\Core\Domain\Repository\PendingTaskRepositoryInterface;
use App\Core\Domain\Repository\ProfileNextTaskRepositoryInterface;
use App\Core\Domain\Service\Task\NextTask\Generator\CategoryNextTaskGenerator;

class GetProfileNextTaskService
{
    public function __construct(
        private ProfileNextTaskRepositoryInterface $nextTaskRepository,
        private CategoryNextTaskGenerator          $nextTaskGenerator,
        private PendingTaskRepositoryInterface     $pendingTaskRepository,
        private EventDispatcherInterface           $eventDispatcher,
        private int                                $taskStackLimit,
    ) {
    }

    /**
     * @param Profile $profile
     * @return Task[]
     */
    public function get(Profile $profile): array
    {
        if ($pending = $this->pendingTaskRepository->get($profile)) {
            return $pending;
        }

        $tasks = $this->prepareNextTasks($profile);
        if (!empty($tasks)) {
            $this->pendingTaskRepository->push($profile, $tasks);
        }

        $this->eventDispatcher->dispatch(new GenerateNextTaskEvent($profile->getId(), $this->taskStackLimit));

        return $tasks;
    }

    private function prepareNextTasks(Profile $profile): array
    {
        $tasks = $this->nextTaskRepository->popAll($profile);
        if (empty($tasks)) {
            $tasks = $this->nextTaskGenerator->generateForProfile($profile, $this->taskStackLimit);
        }
        return $tasks;
    }
}

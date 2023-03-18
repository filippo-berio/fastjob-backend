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
    const MINIMAL_PENDING_STACK = 3;

    public function __construct(
        private ProfileNextTaskRepositoryInterface $nextTaskRepository,
        private CategoryNextTaskGenerator          $nextTaskGenerator,
        private PendingTaskRepositoryInterface     $pendingTaskRepository,
        private EventDispatcherInterface           $eventDispatcher,
    ) {
    }

    /**
     * @param Profile $profile
     * @return Task[]
     */
    public function get(Profile $profile): array
    {
        if ($pending = $this->pendingTaskRepository->get($profile)) {
            if (count($pending) < self::MINIMAL_PENDING_STACK) {
                $this->eventDispatcher->dispatch(new GenerateNextTaskEvent($profile->getId()));
            }
            return $pending;
        }

        $tasks = $this->nextTaskRepository->popAll($profile) ?:
            $this->nextTaskGenerator->generateForProfile($profile);

        if ($tasks) {
            $this->pendingTaskRepository->push($profile, $tasks);
            $this->eventDispatcher->dispatch(new GenerateNextTaskEvent($profile->getId()));
        }


        return $tasks;
    }

}

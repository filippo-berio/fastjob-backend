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
        private int                                $minimalStack,
        private int                                $stackLimit,
    ) {
    }

    public function get(Profile $profile): ?Task
    {
        if ($pending = $this->pendingTaskRepository->get($profile)) {
            return $pending;
        }

        $task = $this->nextTaskRepository->pop($profile);

        if ($task) {
            $stackCount = $this->nextTaskRepository->count($profile);
            if ($stackCount < $this->minimalStack) {
                $this->eventDispatcher->dispatch(new GenerateNextTaskEvent($profile->getId(), $this->stackLimit));
            }
            $this->pendingTaskRepository->set($profile, $task);
            return $task;
        }

        $generatedTasks = $this->nextTaskGenerator->generateForProfile($profile);
        if (empty($generatedTasks)) {
            return null;
        }

        $this->eventDispatcher->dispatch(new GenerateNextTaskEvent($profile->getId(), $this->stackLimit));
        $this->pendingTaskRepository->set($profile, $generatedTasks[0]);
        return $generatedTasks[0];
    }
}

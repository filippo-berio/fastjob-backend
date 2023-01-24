<?php

namespace App\Core\Service\Task\NextTask;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Message\Task\GenerateNextTaskMessage;
use App\Core\Repository\PendingTaskRepository;
use App\Core\Repository\ProfileNextTaskRepository;
use App\Core\Service\Task\NextTask\Generator\CategoryNextTaskGenerator;
use Symfony\Component\Messenger\MessageBusInterface;

class GetProfileNextTaskService
{
    public function __construct(
        private ProfileNextTaskRepository $nextTaskRepository,
        private CategoryNextTaskGenerator $nextTaskGenerator,
        private MessageBusInterface       $messageBus,
        private PendingTaskRepository     $pendingTaskRepository,
        private int                       $minimalStack,
        private int                       $stackLimit,
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
                $this->messageBus->dispatch(new GenerateNextTaskMessage($profile->getId(), $this->stackLimit));
            }
            $this->pendingTaskRepository->set($profile, $task);
            return $task;
        }

        $generatedTasks = $this->nextTaskGenerator->generateForProfile($profile);
        if (empty($generatedTasks)) {
            return null;
        }

        $this->messageBus->dispatch(new GenerateNextTaskMessage($profile->getId(), $this->stackLimit));
        $this->pendingTaskRepository->set($profile, $generatedTasks[0]);
        return $generatedTasks[0];
    }
}

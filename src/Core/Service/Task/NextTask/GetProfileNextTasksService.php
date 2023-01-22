<?php

namespace App\Core\Service\Task\NextTask;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Exception\Task\NextTaskCountExceedsLimitException;
use App\Core\Message\Task\GenerateNextTaskMessage;
use App\Core\Repository\ProfileNextTaskRepository;
use App\Core\Service\Task\NextTask\Generator\CategoryNextTaskGenerator;
use Symfony\Component\Messenger\MessageBusInterface;

class GetProfileNextTasksService
{
    public function __construct(
        private ProfileNextTaskRepository $nextTaskRepository,
        private CategoryNextTaskGenerator $nextTaskGenerator,
        private MessageBusInterface       $messageBus,
    ) {
    }

    /**
     * @param Profile $profile
     * @param int $count
     * @return Task[]
     */
    public function get(Profile $profile, int $count): array
    {
        if ($count > ProfileNextTaskRepository::STACK_LIMIT) {
            throw new NextTaskCountExceedsLimitException();
        }

        $tasks = $this->nextTaskRepository->pop($profile, $count);
        if (count($tasks) === $count) {
            $this->messageBus->dispatch(new GenerateNextTaskMessage($profile->getId(), ProfileNextTaskRepository::STACK_LIMIT));
            return $tasks;
        }

        $generatedTasks = $this->nextTaskGenerator->generateForProfile($profile, $count - count($tasks));
        $tasks += $generatedTasks;

        $this->messageBus->dispatch(new GenerateNextTaskMessage($profile->getId(), ProfileNextTaskRepository::STACK_LIMIT - count($tasks)));

        return $tasks;
    }
}

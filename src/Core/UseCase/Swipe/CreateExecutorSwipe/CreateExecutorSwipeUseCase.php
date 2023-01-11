<?php

namespace App\Core\UseCase\Swipe\CreateExecutorSwipe;

use App\Core\Data\Query\Profile\FindProfileById\FindProfileById;
use App\Core\Data\Query\Task\FindTaskById;
use App\Core\Entity\ExecutorSwipe;
use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Exception\Profile\ProfileNotFoundException;
use App\Core\Exception\Task\TaskNotFoundException;
use App\Core\Service\ExecutorSwipe\CreateExecutorSwipeService;
use App\CQRS\Bus\QueryBusInterface;

class CreateExecutorSwipeUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CreateExecutorSwipeService $createExecutorSwipeService
    ) {
    }

    public function __invoke(
        Profile $user,
        int $taskId,
        int $executorId,
        string $type
    ): ExecutorSwipe {
        $task = $this->getTask($taskId);
        $executor = $this->getProfile($executorId);
        return $this->createExecutorSwipeService->create($user, $task, $executor, $type);
    }

    private function getTask(int $id): Task
    {
        $task = $this->queryBus->handle(new FindTaskById($id));
        if (!$task) {
            throw new TaskNotFoundException();
        }
        return $task;
    }

    private function getProfile(int $id): Profile
    {
        $profile = $this->queryBus->handle(new FindProfileById($id));
        if (!$profile) {
            throw new ProfileNotFoundException();
        }
        return $profile;
    }
}

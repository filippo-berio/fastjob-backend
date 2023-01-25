<?php

namespace App\Core\UseCase\Swipe;

use App\Core\Entity\ExecutorSwipe;
use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Exception\Profile\ProfileNotFoundException;
use App\Core\Exception\Task\TaskNotFoundException;
use App\Core\Query\Profile\FindProfileById\FindProfileById;
use App\Core\Query\Task\FindById\FindTaskById;
use App\Core\Service\ExecutorSwipe\CreateExecutorSwipeService;
use App\CQRS\Bus\QueryBusInterface;

class CreateExecutorSwipeUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CreateExecutorSwipeService $createExecutorSwipeService,
    ) {
    }

    public function create(
        Profile $profile,
        int $taskId,
        int $executorId,
        string $type
    ): ExecutorSwipe {
        $task = $this->getTask($taskId);
        $executor = $this->getProfile($executorId);
        return $this->createExecutorSwipeService->create($profile, $task, $executor, $type);
    }

    private function getTask(int $id): Task
    {
        $task = $this->queryBus->query(new FindTaskById($id));
        if (!$task) {
            throw new TaskNotFoundException();
        }
        return $task;
    }

    private function getProfile(int $id): Profile
    {
        $profile = $this->queryBus->query(new FindProfileById($id));
        if (!$profile) {
            throw new ProfileNotFoundException();
        }
        return $profile;
    }
}

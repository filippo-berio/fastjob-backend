<?php

namespace App\Core\Application\UseCase\Swipe;

use App\Core\Domain\Entity\ExecutorSwipe;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Exception\Profile\ProfileNotFoundException;
use App\Core\Domain\Exception\Task\TaskNotFoundException;
use App\Core\Domain\Query\Profile\FindProfileById\FindProfileById;
use App\Core\Domain\Query\Task\FindById\FindTaskById;
use App\Core\Domain\Service\ExecutorSwipe\CreateExecutorSwipeService;
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

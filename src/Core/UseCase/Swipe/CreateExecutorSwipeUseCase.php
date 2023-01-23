<?php

namespace App\Core\UseCase\Swipe;

use App\Auth\Entity\User;
use App\Core\Entity\ExecutorSwipe;
use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Exception\Profile\ProfileNotFoundException;
use App\Core\Exception\Task\TaskNotFoundException;
use App\Core\Query\Profile\FindByUser\FindProfileByUser;
use App\Core\Query\Profile\FindProfileById\FindProfileById;
use App\Core\Query\Task\FindById\FindTaskById;
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
        User $user,
        int $taskId,
        int $executorId,
        string $type
    ): ExecutorSwipe {
        $task = $this->getTask($taskId);
        $executor = $this->getProfile($executorId);
        $profile = $this->queryBus->query(new FindProfileByUser($user));
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

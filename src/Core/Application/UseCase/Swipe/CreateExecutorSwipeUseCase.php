<?php

namespace App\Core\Application\UseCase\Swipe;

use App\Core\Domain\Entity\NextExecutor;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Exception\Profile\ProfileNotFoundException;
use App\Core\Application\Exception\Task\TaskNotFoundException;
use App\Core\Domain\Query\Profile\FindProfileById;
use App\Core\Domain\Query\Task\FindTaskByAuthorAndId;
use App\Core\Domain\Service\Executor\NextExecutorService\NextExecutorServiceFactory;
use App\Core\Domain\Service\ExecutorSwipe\CreateExecutorSwipeService;
use App\CQRS\Bus\QueryBusInterface;

class CreateExecutorSwipeUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CreateExecutorSwipeService $createExecutorSwipeService,
        private NextExecutorServiceFactory $nextExecutorServiceFactory,
    ) {
    }

    public function create(
        Profile $profile,
        int     $taskId,
        int     $executorId,
        string  $type,
        ?string  $nextExecutorType = null,
    ): ?NextExecutor {
        $task = $this->getTask($profile, $taskId);
        $executor = $this->getProfile($executorId);
        $this->createExecutorSwipeService->create($profile, $task, $executor, $type);
        return $nextExecutorType
            ? $this->nextExecutorServiceFactory->getService($nextExecutorType)->get($profile)
            : null;
    }

    private function getTask(Profile $profile, int $id): Task
    {
        $task = $this->queryBus->query(new FindTaskByAuthorAndId($profile, $id));
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

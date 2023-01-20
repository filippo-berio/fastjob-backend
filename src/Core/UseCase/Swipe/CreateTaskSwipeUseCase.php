<?php

namespace App\Core\UseCase\Swipe;

use App\Auth\Entity\User;
use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Entity\TaskSwipe;
use App\Core\Exception\Task\TaskNotFoundException;
use App\Core\Exception\TaskSwipe\TaskSwipeExistsException;
use App\Core\Query\ExecutorSwipe\FindByProfileTask\FindExecutorSwipeByProfileTask;
use App\Core\Query\Profile\FindByUser\FindProfileByUser;
use App\Core\Query\Task\FindTaskById;
use App\Core\Service\TaskSwipe\CreateTaskSwipeService;
use App\CQRS\Bus\QueryBusInterface;

class CreateTaskSwipeUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CreateTaskSwipeService $createTaskSwipeService
    ) {
    }

    public function __invoke(
        User $user,
        int $taskId,
        string $type,
        ?int $customPrice = null
    ): TaskSwipe {
        $profile = $this->queryBus->handle(new FindProfileByUser($user));
        $task = $this->getTask($taskId);
        $this->checkExisting($profile, $task);
        return $this->createTaskSwipeService->create($profile, $task, $type, $customPrice);
    }

    private function getTask(int $id): Task
    {
        $task = $this->queryBus->handle(new FindTaskById($id));
        if (!$task) {
            throw new TaskNotFoundException();
        }
        return $task;
    }

    private function checkExisting(Profile $profile, Task $task)
    {
        $taskSwipe = $this->queryBus->handle(new FindExecutorSwipeByProfileTask($profile, $task));
        if ($taskSwipe) {
            throw new TaskSwipeExistsException();
        }
    }
}

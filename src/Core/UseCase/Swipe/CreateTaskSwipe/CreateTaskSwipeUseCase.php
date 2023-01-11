<?php

namespace App\Core\UseCase\Swipe\CreateTaskSwipe;

use App\Core\Data\Query\Task\FindTaskById;
use App\Core\Data\Query\TaskSwipe\FindByProfileTask\FindExecutorSwipeByProfileTask;
use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Entity\TaskSwipe;
use App\Core\Exception\Task\TaskNotFoundException;
use App\Core\Exception\TaskSwipe\TaskSwipeExistsException;
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
        Profile $user,
        int $taskId,
        string $type,
        ?int $customPrice = null
    ): TaskSwipe {
        $task = $this->getTask($taskId);
        $this->checkExisting($user, $task);
        $this->createTaskSwipeService->create($user, $task, $type, $customPrice);
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

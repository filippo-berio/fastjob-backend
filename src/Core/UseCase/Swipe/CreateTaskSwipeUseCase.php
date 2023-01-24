<?php

namespace App\Core\UseCase\Swipe;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Exception\Task\TaskNotFoundException;
use App\Core\Query\Task\FindById\FindTaskById;
use App\Core\Service\Task\NextTask\GetProfileNextTaskService;
use App\Core\Service\TaskSwipe\CreateTaskSwipeService;
use App\CQRS\Bus\QueryBusInterface;

class CreateTaskSwipeUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CreateTaskSwipeService $createTaskSwipeService,
        private GetProfileNextTaskService $getProfileNextTaskService,
    ) {
    }

    public function create(
        Profile $profile,
        int $taskId,
        string $type,
        ?int $customPrice = null
    ): ?Task {
        $task = $this->queryBus->query(new FindTaskById($taskId));
        if (!$task) {
            throw new TaskNotFoundException();
        }
        $this->createTaskSwipeService->create($profile, $task, $type, $customPrice);
        return $this->getProfileNextTaskService->get($profile);
    }
}

<?php

namespace App\Core\Application\UseCase\Swipe;

use App\Core\Application\Exception\Task\TaskNotFoundException;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Query\Task\FindTaskById;
use App\Core\Domain\Service\Task\NextTask\GetProfileNextTaskService;
use App\Core\Domain\Service\TaskSwipe\CreateTaskSwipeService;
use App\Lib\CQRS\Bus\QueryBusInterface;

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
        int     $taskId,
        string  $type,
        ?int    $customPrice = null
    ): ?Task {
        $task = $this->queryBus->query(new FindTaskById($taskId));
        if (!$task) {
            throw new TaskNotFoundException();
        }
        $this->createTaskSwipeService->create($profile, $task, $type, $customPrice);
        return $this->getProfileNextTaskService->get($profile);
    }
}

<?php

namespace App\Core\UseCase\Task\Accept;

use App\Core\Data\Query\Task\FindTaskById;
use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Entity\TaskResponse;
use App\Core\Exception\Task\TaskNotFoundException;
use App\Core\Service\TaskResponse\SaveTaskResponseService;
use App\CQRS\Bus\QueryBusInterface;

class AcceptTaskUseCase
{
    public function __construct(
        private QueryBusInterface       $queryBus,
        private SaveTaskResponseService $saveTaskResponseService,
    ) {
    }

    public function __invoke(
        Profile $profile,
        int     $taskId,
        ?int    $customPrice = null,
    ) {
        /** @var Task $task */
        $task = $this->queryBus->handle(new FindTaskById($taskId));
        if (!$task) {
            throw new TaskNotFoundException();
        }
        $taskResponse = new TaskResponse($task, $profile, true, $customPrice);
        $this->saveTaskResponseService->save($taskResponse);
    }
}

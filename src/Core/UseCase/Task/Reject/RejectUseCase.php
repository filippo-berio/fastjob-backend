<?php

namespace App\Core\UseCase\Task\Reject;

use App\Core\Data\Query\Task\FindTaskById;
use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Entity\TaskResponse;
use App\Core\Exception\Task\TaskNotFoundException;
use App\Core\Service\TaskResponse\SaveTaskResponseService;
use App\CQRS\Bus\QueryBusInterface;

class RejectUseCase
{
    public function __construct(
        private QueryBusInterface       $queryBus,
        private SaveTaskResponseService $saveTaskResponseService,
    ) {
    }

    public function __invoke(Profile $profile, int $taskId)
    {
        /** @var Task $task */
        $task = $this->queryBus->handle(new FindTaskById($taskId));
        if (!$task) {
            throw new TaskNotFoundException();
        }
        $taskResponse = new TaskResponse($task, $profile, false);
        $this->saveTaskResponseService->save($taskResponse);
    }
}

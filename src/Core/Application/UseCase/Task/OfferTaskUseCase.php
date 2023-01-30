<?php

namespace App\Core\Application\UseCase\Task;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Exception\Profile\ProfileNotFoundException;
use App\Core\Application\Exception\Task\TaskNotFoundException;
use App\Core\Domain\Query\Profile\FindProfileById;
use App\Core\Domain\Query\Task\FindTaskByAuthorAndId;
use App\Core\Domain\Service\Task\OfferTaskService;
use App\CQRS\Bus\QueryBusInterface;

class OfferTaskUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private OfferTaskService  $offerTaskService,
    ) {
    }

    public function offer(Profile $profile, int $taskId, int $executorId)
    {
        $task = $this->queryBus->query(new FindTaskByAuthorAndId($profile, $taskId));
        if (!$task) {
            throw new TaskNotFoundException();
        }

        $executor = $this->queryBus->query(new FindProfileById($executorId));
        if (!$executor) {
            throw new ProfileNotFoundException();
        }

        $this->offerTaskService->offer($task, $executor);
    }
}

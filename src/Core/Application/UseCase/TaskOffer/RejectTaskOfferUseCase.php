<?php

namespace App\Core\Application\UseCase\TaskOffer;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Exception\TaskOffer\TaskOfferNotFoundException;
use App\Core\Domain\Query\Task\FindTaskById;
use App\Core\Domain\Service\TaskOffer\RejectTaskOfferService;
use App\CQRS\Bus\QueryBusInterface;

class RejectTaskOfferUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private RejectTaskOfferService $rejectTaskOfferService,
    ) {
    }

    public function reject(Profile $profile, int $taskId)
    {
        $task = $this->queryBus->query(new FindTaskById($taskId));
        if (!$task) {
            throw new TaskOfferNotFoundException();
        }
        $this->rejectTaskOfferService->reject($profile, $task);
    }
}

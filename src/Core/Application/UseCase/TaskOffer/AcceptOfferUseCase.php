<?php

namespace App\Core\Application\UseCase\TaskOffer;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Exception\TaskOffer\TaskOfferNotFoundException;
use App\Core\Domain\Query\Task\FindTaskById;
use App\Core\Domain\Service\TaskOffer\AcceptOfferService;
use App\CQRS\Bus\QueryBusInterface;

class AcceptOfferUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private AcceptOfferService $acceptOfferService,
    ) {
    }

    public function acceptOffer(Profile $profile, int $taskId)
    {
        $task = $this->queryBus->query(new FindTaskById($taskId));
        if (!$task) {
            throw new TaskOfferNotFoundException();
        }
        $this->acceptOfferService->accept($profile, $task);
    }
}

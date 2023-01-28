<?php

namespace App\Core\Domain\Service\TaskOffer;

use App\Core\Domain\Command\Task\Save\SaveTask;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Exception\TaskOffer\TaskOfferNotFoundException;
use App\Core\Domain\Repository\TaskOfferRepositoryInterface;
use App\CQRS\Bus\CommandBusInterface;

class RejectTaskOfferService
{
    public function __construct(
        private TaskOfferRepositoryInterface $taskOfferRepository,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function reject(Profile $profile, Task $task)
    {
        $offer = $this->taskOfferRepository->findByProfileAndTask($profile, $task);
        if (!$offer || $offer->isAccepted()) {
            throw new TaskOfferNotFoundException();
        }

        $offer->accept();
        $this->taskOfferRepository->save($offer);

        $offer->getTask()->resetStatus();
        $this->commandBus->execute(new SaveTask($offer->getTask()));
    }
}

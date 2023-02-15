<?php

namespace App\Core\Domain\Service\TaskOffer;

use App\Core\Domain\Command\Task\Save\SaveTask;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Exception\TaskOffer\TaskOfferNotFoundException;
use App\Core\Domain\Repository\TaskOfferRepositoryInterface;
use App\Lib\CQRS\Bus\CommandBusInterface;

class AcceptOfferService
{
    public function __construct(
        private TaskOfferRepositoryInterface $taskOfferRepository,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function accept(Profile $profile, Task $task)
    {
        $offer = $this->taskOfferRepository->findByProfileAndTask($profile, $task);
        if (!$offer) {
            throw new TaskOfferNotFoundException();
        }

        $offer->accept();
        $this->taskOfferRepository->save($offer);

        $offer->getTask()->acceptOffer();
        $this->commandBus->execute(new SaveTask($offer->getTask()));
    }
}

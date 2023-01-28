<?php

namespace App\Core\Domain\Service\TaskOffer;

use App\Core\Domain\Event\EventDispatcherInterface;
use App\Core\Domain\Event\TaskOffer\Cancel\CancelTaskOffersEvent;
use App\Core\Domain\Repository\TaskOfferRepositoryInterface;

class CancelExpiredTaskOffersService
{
    public function __construct(
        private TaskOfferRepositoryInterface $taskOfferRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function cancel()
    {
        $offers = $this->taskOfferRepository->findExpired();
        $this->eventDispatcher->dispatch(new CancelTaskOffersEvent($offers));
    }
}

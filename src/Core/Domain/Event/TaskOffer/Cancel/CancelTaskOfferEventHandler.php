<?php

namespace App\Core\Domain\Event\TaskOffer\Cancel;

use App\Core\Domain\Event\EventHandlerInterface;
use App\Core\Domain\Event\EventInterface;
use App\Core\Domain\Repository\TaskOfferRepositoryInterface;

class CancelTaskOfferEventHandler implements EventHandlerInterface
{

    public function __construct(
        private TaskOfferRepositoryInterface $taskOfferRepository,
    ) {
    }

    public function event(): string
    {
        return CancelTaskOffersEvent::class;
    }

    /**
     * @param CancelTaskOffersEvent $event
     * @return void
     */
    public function handle(EventInterface $event)
    {
        foreach ($event->offers as $offer) {
            $offer->cancel();
            $this->taskOfferRepository->save($offer);
        }
    }
}

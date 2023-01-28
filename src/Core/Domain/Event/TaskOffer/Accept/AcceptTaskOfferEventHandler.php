<?php

namespace App\Core\Domain\Event\TaskOffer\Accept;

use App\Core\Domain\Event\EventHandlerInterface;
use App\Core\Domain\Event\EventInterface;

class AcceptTaskOfferEventHandler implements EventHandlerInterface
{
    public function event(): string
    {
        return AcceptTaskOfferEvent::class;
    }

    /**
     * @param AcceptTaskOfferEvent $event
     * @return void
     */
    public function handle(EventInterface $event)
    {

    }
}

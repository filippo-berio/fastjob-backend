<?php

namespace App\Core\Domain\Event\TaskOffer\Cancel;

use App\Core\Domain\Entity\TaskOffer;
use App\Core\Domain\Event\EventInterface;

class CancelTaskOffersEvent implements EventInterface
{
    /**
     * @param TaskOffer[] $offers
     */
    public function __construct(
        public array $offers
    ) {
    }

    public function executionType(): string
    {
        return EventInterface::EXECUTION_TYPE_ASYNC;
    }
}

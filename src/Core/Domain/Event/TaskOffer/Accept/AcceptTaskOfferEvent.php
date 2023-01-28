<?php

namespace App\Core\Domain\Event\TaskOffer\Accept;

use App\Core\Domain\Entity\TaskOffer;
use App\Core\Domain\Event\EventInterface;

class AcceptTaskOfferEvent implements EventInterface
{
    public function __construct(
        public TaskOffer $offer
    ) {
    }

    public function executionType(): string
    {
        return self::EXECUTION_TYPE_ASYNC;
    }
}

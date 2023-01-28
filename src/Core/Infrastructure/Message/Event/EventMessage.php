<?php

namespace App\Core\Infrastructure\Message\Event;

use App\Core\Domain\Event\EventInterface;

class EventMessage
{
    public function __construct(
        public EventInterface $event,
    ) {
    }
}

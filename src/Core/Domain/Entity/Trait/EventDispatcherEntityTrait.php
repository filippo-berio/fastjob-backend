<?php

namespace App\Core\Domain\Entity\Trait;

use App\Core\Domain\Event\EventDispatcherInterface;
use App\Core\Domain\Event\EventInterface;

trait EventDispatcherEntityTrait
{
    private EventDispatcherInterface $eventDispatcher;

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function dispatch(EventInterface $event)
    {
        $this->eventDispatcher->dispatch($event);
    }
}

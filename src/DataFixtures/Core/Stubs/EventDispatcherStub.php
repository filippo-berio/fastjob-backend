<?php

namespace App\DataFixtures\Core\Stubs;

use App\Core\Domain\Event\EventDispatcherInterface;
use App\Core\Domain\Event\EventInterface;

class EventDispatcherStub implements EventDispatcherInterface
{

    public function dispatch(EventInterface $event)
    {
    }
}

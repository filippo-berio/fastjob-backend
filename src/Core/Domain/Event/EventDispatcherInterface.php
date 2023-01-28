<?php

namespace App\Core\Domain\Event;

interface EventDispatcherInterface
{
    public function dispatch(EventInterface $event);
}

<?php

namespace App\Core\Domain\Event;

interface EventHandlerInterface
{
    public function event(): string;

    public function handle(EventInterface $event);
}

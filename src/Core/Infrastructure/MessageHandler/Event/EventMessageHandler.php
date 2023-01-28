<?php

namespace App\Core\Infrastructure\MessageHandler\Event;

use App\Core\Infrastructure\Event\EventHandler;
use App\Core\Infrastructure\Message\Event\EventMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EventMessageHandler
{
    public function __construct(
        private EventHandler $eventHandler,
    ) {
    }

    public function __invoke(EventMessage $message)
    {
        $this->eventHandler->handle($message->event);
    }
}

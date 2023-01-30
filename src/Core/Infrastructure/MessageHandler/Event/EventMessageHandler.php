<?php

namespace App\Core\Infrastructure\MessageHandler\Event;

use App\Core\Domain\Event\EventHandlerInterface;
use App\Core\Infrastructure\Event\EventHandlerFactory;
use App\Core\Infrastructure\Message\Event\EventMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EventMessageHandler
{
    public function __construct(
        private EventHandlerFactory $eventHandlerFactory,
    ) {
    }

    public function __invoke(EventMessage $message)
    {
        $handlers = $this->eventHandlerFactory->getEventHandlers(
            $message->event,
            EventHandlerInterface::EXECUTION_TYPE_ASYNC
        );
        foreach ($handlers as $eventHandler) {
            $eventHandler->handle($message->event);
        }
    }
}

<?php

namespace App\Core\Infrastructure\Event;

use App\Core\Domain\Event\EventHandlerInterface;
use App\Core\Domain\Event\EventInterface;
use App\Core\Domain\Event\EventDispatcherInterface;
use App\Core\Infrastructure\Message\Event\EventMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class EventDispatcher implements EventDispatcherInterface
{

    public function __construct(
        private MessageBusInterface $messageBus,
        private EventHandlerFactory $eventHandlerFactory,
    ) {
    }

    public function dispatch(EventInterface $event)
    {
        $nowHandlers = $this->eventHandlerFactory->getEventHandlers(
            $event,
            EventHandlerInterface::EXECUTION_TYPE_NOW
        );
        foreach ($nowHandlers as $handler) {
            $handler->handle($event);
        }

        $asyncHandlers = $this->eventHandlerFactory->getEventHandlers(
            $event,
            EventHandlerInterface::EXECUTION_TYPE_ASYNC
        );
        if (!empty($asyncHandlers)) {
            $this->messageBus->dispatch(new EventMessage($event));
        }
    }
}

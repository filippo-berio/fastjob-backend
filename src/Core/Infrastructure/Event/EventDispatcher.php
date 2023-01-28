<?php

namespace App\Core\Infrastructure\Event;

use App\Core\Domain\Event\EventInterface;
use App\Core\Domain\Event\EventDispatcherInterface;
use App\Core\Infrastructure\Message\Event\EventMessage;
use Exception;
use Symfony\Component\Messenger\MessageBusInterface;

class EventDispatcher implements EventDispatcherInterface
{

    public function __construct(
        private MessageBusInterface $messageBus,
        private EventHandler $eventHandler,
    ) {
    }

    public function dispatch(EventInterface $event)
    {
        if ($event->executionType() === EventInterface::EXECUTION_TYPE_NOW) {
            return $this->eventHandler->handle($event);
        }
        if ($event->executionType() === EventInterface::EXECUTION_TYPE_ASYNC) {
            return $this->messageBus->dispatch(new EventMessage($event));
        }

        throw new Exception('Неизвестный тип события ' . $event->executionType());
    }
}

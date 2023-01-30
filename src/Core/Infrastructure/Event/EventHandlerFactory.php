<?php

namespace App\Core\Infrastructure\Event;

use App\Core\Domain\Event\EventHandlerInterface;
use App\Core\Domain\Event\EventInterface;
use Exception;

class EventHandlerFactory
{
    /** @var EventHandlerInterface[][][] */
    private array $eventHandlers;

    public function __construct(
        iterable $handlers,
    ) {
        foreach ($handlers as $handler) {
            $this->register($handler);
        }
    }

    /**
     * @param EventInterface $event
     * @param string $executionType
     * @return EventHandlerInterface[]
     */
    public function getEventHandlers(
        EventInterface $event,
        string $executionType,
    ): array {
        return $this->eventHandlers[$event::class][$executionType] ?? [];
    }

    private function register(EventHandlerInterface $handler)
    {
        if (!isset($this->eventHandlers[$handler->event()])) {
            $this->eventHandlers[$handler->event()] = [];
        }
        if (!isset($this->eventHandlers[$handler->event()][$handler->executionType()])) {
            $this->eventHandlers[$handler->event()][$handler->executionType()] = [];
        }
        $this->eventHandlers[$handler->event()][$handler->executionType()][] = $handler;
    }
}

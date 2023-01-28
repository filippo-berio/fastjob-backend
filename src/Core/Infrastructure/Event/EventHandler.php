<?php

namespace App\Core\Infrastructure\Event;

use App\Core\Domain\Event\EventHandlerInterface;
use App\Core\Domain\Event\EventInterface;
use Exception;

class EventHandler
{
    /** @var EventHandlerInterface[] */
    private array $eventHandlers;

    /**
     * @param iterable<EventHandlerInterface> $handlers
     */
    public function __construct(
        iterable $handlers,
    ) {
        foreach ($handlers as $handler) {
            $this->eventHandlers[$handler->event()] = $handler;
        }
    }

    public function handle(EventInterface $event)
    {
        if (!isset($this->eventHandlers[$event::class])) {
            throw new Exception('Не найден хендлер для ' . $event::class);
        }
        $handler = $this->eventHandlers[$event::class];
        $handler->handle($event);
    }
}

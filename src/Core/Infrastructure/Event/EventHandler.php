<?php

namespace App\Core\Infrastructure\Event;

use App\Core\Domain\Event\EventHandlerInterface;
use App\Core\Domain\Event\EventInterface;
use Exception;

class EventHandler
{
    /** @var EventHandlerInterface[][] */
    private array $eventHandlers;

    public function __construct(
        iterable $handlers,
    ) {
        foreach ($handlers as $handler) {
            $this->register($handler);
        }
    }

    public function handle(EventInterface $event)
    {
        if (!isset($this->eventHandlers[$event::class])) {
            throw new Exception('Не найден хендлер для ' . $event::class);
        }
        foreach ($this->eventHandlers[$event::class] as $handler) {
            $handler->handle($event);
        }
    }

    private function register(EventHandlerInterface $handler)
    {
        if (!isset($this->eventHandlers[$handler->event()])) {
            $this->eventHandlers[$handler->event()] = [];
        }
        $this->eventHandlers[$handler->event()][] = $handler;
    }
}

<?php

namespace App\Core\Domain\Event;

interface EventHandlerInterface
{
    const EXECUTION_TYPE_NOW = 'now';
    const EXECUTION_TYPE_ASYNC = 'async';

    public function executionType(): string;

    public function event(): string;

    public function handle(EventInterface $event);
}

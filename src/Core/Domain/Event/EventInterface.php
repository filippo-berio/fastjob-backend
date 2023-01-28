<?php

namespace App\Core\Domain\Event;


interface EventInterface
{
    const EXECUTION_TYPE_NOW = 'now';
    const EXECUTION_TYPE_ASYNC = 'async';

    public function executionType(): string;
}

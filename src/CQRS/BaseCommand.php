<?php

namespace App\CQRS;

abstract class BaseCommand implements CommandInterface
{
    public function getHandlerClass(): string
    {
        return $this::class . 'Handler';
    }
}

<?php

namespace App\CQRS;

abstract class BaseQuery implements QueryInterface
{

    public function getHandlerClass(): string
    {
        return $this::class . 'Handler';
    }
}

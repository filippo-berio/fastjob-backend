<?php

namespace App\CQRS;

interface QueryInterface
{
    public function getHandlerClass(): string;
}

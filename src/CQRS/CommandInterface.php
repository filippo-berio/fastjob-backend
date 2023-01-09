<?php

namespace App\CQRS;

interface CommandInterface
{
    public function getHandlerClass(): string;
}

<?php

namespace App\Lib\CQRS;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command): mixed;

    public function getCommandClass(): string;
}

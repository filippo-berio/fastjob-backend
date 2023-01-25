<?php

namespace App\CQRS;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command): mixed;

    public function getCommandClass(): string;
}

<?php

namespace App\CQRS\Bus;

use App\CQRS\CommandInterface;

interface CommandBusInterface
{
    public function handle(CommandInterface $command): mixed;
}

<?php

namespace App\CQRS\Bus;

use App\CQRS\CommandInterface;

interface CommandBusInterface
{
    public function execute(CommandInterface $command): mixed;

    public function executeAsync(CommandInterface $command);
}

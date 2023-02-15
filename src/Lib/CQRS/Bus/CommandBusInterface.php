<?php

namespace App\Lib\CQRS\Bus;

use App\Lib\CQRS\CommandInterface;

interface CommandBusInterface
{
    public function execute(CommandInterface $command): mixed;

    public function executeAsync(CommandInterface $command);
}

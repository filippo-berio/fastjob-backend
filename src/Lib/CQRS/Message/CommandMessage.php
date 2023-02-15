<?php

namespace App\Lib\CQRS\Message;

use App\Lib\CQRS\CommandInterface;

readonly class CommandMessage
{
    public function __construct(
        public CommandInterface $command
    ) {
    }
}

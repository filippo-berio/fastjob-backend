<?php

namespace App\CQRS\Message;

use App\CQRS\CommandInterface;

readonly class CommandMessage
{
    public function __construct(
        public CommandInterface $command
    ) {
    }
}

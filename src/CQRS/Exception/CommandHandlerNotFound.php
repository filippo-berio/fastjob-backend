<?php

namespace App\CQRS\Exception;

use App\CQRS\CommandInterface;
use Exception;

class CommandHandlerNotFound extends Exception
{
    public function __construct(CommandInterface $command)
    {
        parent::__construct('Не найден CommandHandler для' . $command::class);
    }
}

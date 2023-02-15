<?php

namespace App\Lib\CQRS\Exception;

use App\Lib\CQRS\CommandInterface;
use Exception;

class CommandHandlerNotFound extends Exception
{
    public function __construct(CommandInterface $command)
    {
        parent::__construct('Не найден CommandHandler для' . $command::class);
    }
}

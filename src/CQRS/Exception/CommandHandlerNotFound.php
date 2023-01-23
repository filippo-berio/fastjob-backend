<?php

namespace App\CQRS\Exception;

use App\CQRS\CommandInterface;

class CommandHandlerNotFound extends \Exception
{
    public function __construct(CommandInterface $command)
    {
        parent::__construct('Не найден CommandHandler ' . $command->getHandlerClass());
    }
}

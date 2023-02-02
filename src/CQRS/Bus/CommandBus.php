<?php

namespace App\CQRS\Bus;

use App\CQRS\CommandInterface;
use App\CQRS\Message\AsyncCommandMessage;
use App\CQRS\Message\CommandMessage;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function execute(CommandInterface $command): mixed
    {
        return $this->handle(new CommandMessage($command));
    }

    public function executeAsync(CommandInterface $command)
    {
        $this->messageBus->dispatch(new AsyncCommandMessage($command));
    }
}

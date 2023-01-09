<?php

namespace App\CQRS\Bus;

use App\CQRS\CommandHandlerInterface;
use App\CQRS\CommandInterface;
use Exception;

class CommandBus implements CommandBusInterface
{
    private array $commandHandlers;

    public function __construct(
        iterable $commandHandlers,
    ) {
        foreach ($commandHandlers as $handler) {
            $this->commandHandlers[$handler::class] = $handler;
        }
    }

    public function handle(CommandInterface $command): mixed
    {
        $handler = $this->getHandler($command);
        return $handler->handle($command);
    }

    private function getHandler(CommandInterface $command): CommandHandlerInterface
    {
        $handlerClass = $command->getHandlerClass();
        $handler = $this->commandHandlers[$command->getHandlerClass()] ?? null;
        if (!$handler) {
            throw new Exception("Не найден commandHandler $handlerClass");
        }
        return $handler;
    }
}

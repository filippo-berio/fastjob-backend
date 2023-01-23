<?php

namespace App\CQRS\MessageHandler;

use App\CQRS\CommandHandlerInterface;
use App\CQRS\CommandInterface;
use App\CQRS\Exception\CommandHandlerNotFound;
use App\CQRS\Message\CommandMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CommandMessageHandler
{
    private array $commandHandlers;

    public function __construct(
        iterable $commandHandlers,
    ) {
        foreach ($commandHandlers as $commandHandler) {
            $this->commandHandlers[$commandHandler::class] = $commandHandler;
        }
    }

    public function __invoke(CommandMessage $message)
    {
        $handler = $this->getHandler($message->command);
        return $handler->handle($message->command);
    }

    private function getHandler(CommandInterface $command): CommandHandlerInterface
    {
        $handler = $this->commandHandlers[$command->getHandlerClass()] ?? null;
        if (!$handler) {
            throw new CommandHandlerNotFound($command);
        }
        return $handler;
    }
}

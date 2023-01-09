<?php

namespace App\CQRS\Bus;

use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Exception;

class QueryBus implements QueryBusInterface
{
    private array $queryHandlers;

    public function __construct(
        iterable $queryHandlers,
    ) {
        foreach ($queryHandlers as $queryHandler) {
            $this->queryHandlers[$queryHandler::class] = $queryHandler;
        }
    }

    public function handle(QueryInterface $query): mixed
    {
        $handler = $this->getHandler($query);
        return $handler->handle($query);
    }

    private function getHandler(QueryInterface $query): QueryHandlerInterface
    {
        $handlerClass = $query->getHandlerClass();
        $handler = $this->queryHandlers[$query->getHandlerClass()] ?? null;
        if (!$handler) {
            throw new Exception("Не найден queryHandler $handlerClass");
        }
        return $handler;
    }
}

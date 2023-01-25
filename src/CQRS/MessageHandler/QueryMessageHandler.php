<?php

namespace App\CQRS\MessageHandler;

use App\CQRS\Exception\QueryHandlerNotFound;
use App\CQRS\Message\QueryMessage;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class QueryMessageHandler
{
    private array $queryHandlers;

    /**
     * @param iterable<QueryHandlerInterface> $queryHandlers
     */
    public function __construct(
        iterable $queryHandlers,
    ) {
        foreach ($queryHandlers as $queryHandler) {
            $this->queryHandlers[$queryHandler->getQueryClass()] = $queryHandler;
        }
    }

    public function __invoke(QueryMessage $message)
    {
        $handler = $this->getHandler($message->query);
        return $handler->handle($message->query);
    }

    private function getHandler(QueryInterface $query): QueryHandlerInterface
    {
        $handler = $this->queryHandlers[$query::class] ?? null;
        if (!$handler) {
            throw new QueryHandlerNotFound($query);
        }
        return $handler;
    }
}

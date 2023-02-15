<?php

namespace App\Lib\CQRS\MessageHandler;

use App\Lib\CQRS\Exception\QueryHandlerNotFound;
use App\Lib\CQRS\Message\QueryMessage;
use App\Lib\CQRS\QueryHandlerInterface;
use App\Lib\CQRS\QueryInterface;
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

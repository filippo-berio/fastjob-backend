<?php

namespace App\CQRS\Bus;

use App\CQRS\Message\QueryMessage;
use App\CQRS\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function query(QueryInterface $query): mixed
    {
        return $this->handle(new QueryMessage($query));
    }
}

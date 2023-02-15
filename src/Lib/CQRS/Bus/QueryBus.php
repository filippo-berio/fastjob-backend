<?php

namespace App\Lib\CQRS\Bus;

use App\Lib\CQRS\Message\QueryMessage;
use App\Lib\CQRS\QueryInterface;
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

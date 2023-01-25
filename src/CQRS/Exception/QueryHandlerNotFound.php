<?php

namespace App\CQRS\Exception;

use App\CQRS\QueryInterface;

class QueryHandlerNotFound extends \Exception
{
    public function __construct(QueryInterface $query)
    {
        parent::__construct('Не найден QueryHandler для' . $query::class);
    }
}

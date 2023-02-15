<?php

namespace App\Lib\CQRS\Exception;

use App\Lib\CQRS\QueryInterface;

class QueryHandlerNotFound extends \Exception
{
    public function __construct(QueryInterface $query)
    {
        parent::__construct('Не найден QueryHandler для' . $query::class);
    }
}

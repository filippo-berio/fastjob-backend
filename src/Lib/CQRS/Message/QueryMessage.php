<?php

namespace App\Lib\CQRS\Message;

use App\Lib\CQRS\QueryInterface;

readonly class QueryMessage
{
    public function __construct(
        public QueryInterface $query,
    ) {
    }
}

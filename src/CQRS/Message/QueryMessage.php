<?php

namespace App\CQRS\Message;

use App\CQRS\QueryInterface;

readonly class QueryMessage
{
    public function __construct(
        public QueryInterface $query,
    ) {
    }
}

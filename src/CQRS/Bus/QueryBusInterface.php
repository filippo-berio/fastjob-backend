<?php

namespace App\CQRS\Bus;

use App\CQRS\QueryInterface;

interface QueryBusInterface
{
    public function handle(QueryInterface $query): mixed;
}

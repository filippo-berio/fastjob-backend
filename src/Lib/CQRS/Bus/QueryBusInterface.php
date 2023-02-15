<?php

namespace App\Lib\CQRS\Bus;

use App\Lib\CQRS\QueryInterface;

interface QueryBusInterface
{
    public function query(QueryInterface $query): mixed;
}

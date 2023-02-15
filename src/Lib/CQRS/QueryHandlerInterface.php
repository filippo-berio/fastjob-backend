<?php

namespace App\Lib\CQRS;

interface QueryHandlerInterface
{
    public function handle(QueryInterface $query): mixed;

    public function getQueryClass(): string;
}

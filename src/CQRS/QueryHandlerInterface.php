<?php

namespace App\CQRS;

interface QueryHandlerInterface
{
    public function handle(QueryInterface $query): mixed;

    public function getQueryClass(): string;
}

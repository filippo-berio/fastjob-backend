<?php

namespace App\Core\Data\Query\Profile;

use App\CQRS\QueryInterface;

class FindByAuthisticId implements QueryInterface
{
    public function __construct(
        public int $authisticId
    ) {
    }

    public function getHandlerClass(): string
    {
        return FindByAuthisticIdHandler::class;
    }
}

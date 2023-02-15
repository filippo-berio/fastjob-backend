<?php

namespace App\Core\Domain\Query\Profile;

use App\Lib\CQRS\QueryInterface;

class FindProfileById implements QueryInterface
{
    public function __construct(
        public int $id
    ) {
    }
}

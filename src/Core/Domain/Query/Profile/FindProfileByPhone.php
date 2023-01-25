<?php

namespace App\Core\Domain\Query\Profile;

use App\CQRS\QueryInterface;

class FindProfileByPhone implements QueryInterface
{
    public function __construct(
        public string $phone
    ) {
    }
}

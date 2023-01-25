<?php

namespace App\Core\Domain\Query\Profile\FindByPhone;

use App\CQRS\BaseQuery;

class FindProfileByPhone extends BaseQuery
{
    public function __construct(
        public string $phone
    ) {
    }
}

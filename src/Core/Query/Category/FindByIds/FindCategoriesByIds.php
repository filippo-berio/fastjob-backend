<?php

namespace App\Core\Query\Category\FindByIds;

use App\CQRS\BaseQuery;

class FindCategoriesByIds extends BaseQuery
{
    public function __construct(
        public array $ids
    ) {
    }
}

<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Entity\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return Category[]
     */
    public function findByIds(array $ids): array;
}

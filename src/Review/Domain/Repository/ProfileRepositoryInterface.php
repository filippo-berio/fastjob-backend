<?php

namespace App\Review\Domain\Repository;

use App\Review\Domain\Entity\Profile;

interface ProfileRepositoryInterface
{
    public function find(int $id): ?Profile;
}

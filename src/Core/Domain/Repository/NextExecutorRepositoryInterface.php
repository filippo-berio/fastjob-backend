<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Entity\NextExecutor;
use App\Core\Domain\Entity\Profile;

interface NextExecutorRepositoryInterface
{
    public function nextSwipedExecutor(Profile $author): ?NextExecutor;
}

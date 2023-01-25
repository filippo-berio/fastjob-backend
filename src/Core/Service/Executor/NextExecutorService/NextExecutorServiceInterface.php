<?php

namespace App\Core\Service\Executor\NextExecutorService;

use App\Core\Entity\NextExecutor;
use App\Core\Entity\Profile;

interface NextExecutorServiceInterface
{
    public function get(Profile $author): ?NextExecutor;
}

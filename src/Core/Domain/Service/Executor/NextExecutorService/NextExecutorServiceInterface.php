<?php

namespace App\Core\Domain\Service\Executor\NextExecutorService;

use App\Core\Domain\Entity\NextExecutor;
use App\Core\Domain\Entity\Profile;

interface NextExecutorServiceInterface
{
    public function get(Profile $author): ?NextExecutor;

    public function getExecutorType(): string;
}

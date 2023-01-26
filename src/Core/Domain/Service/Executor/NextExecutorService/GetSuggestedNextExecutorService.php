<?php

namespace App\Core\Domain\Service\Executor\NextExecutorService;

use App\Core\Domain\Entity\NextExecutor;
use App\Core\Domain\Entity\Profile;

class GetSuggestedNextExecutorService extends BaseNextExecutorService
{
    const TYPE = 'suggested';

    public function getExecutorType(): string
    {
        return self::TYPE;
    }

    public function get(Profile $author): ?NextExecutor
    {
        $this->checkTasks($author);
        return $this->nextExecutorRepository->getSuggestedNextExecutor($author);
    }
}

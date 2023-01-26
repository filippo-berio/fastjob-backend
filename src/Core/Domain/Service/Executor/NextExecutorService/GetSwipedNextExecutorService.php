<?php

namespace App\Core\Domain\Service\Executor\NextExecutorService;

use App\Core\Domain\Entity\NextExecutor;
use App\Core\Domain\Entity\Profile;

class GetSwipedNextExecutorService extends BaseNextExecutorService
{
    const TYPE = 'swiped';

    public function getExecutorType(): string
    {
        return self::TYPE;
    }

    public function get(Profile $author): ?NextExecutor
    {
        $this->checkTasks($author);
        return $this->nextExecutorRepository->nextSwipedExecutor($author);
    }
}

<?php

namespace App\Core\Application\UseCase\Executor;

use App\Core\Domain\Entity\NextExecutor;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Service\Executor\NextExecutorService\GetSwipedNextExecutorService;

class GetSwipedNextExecutorUseCase
{
    public function __construct(
        private GetSwipedNextExecutorService $getSwipedNextExecutorService,
    ) {
    }

    public function get(Profile $profile): ?NextExecutor
    {
        return $this->getSwipedNextExecutorService->get($profile);
    }
}

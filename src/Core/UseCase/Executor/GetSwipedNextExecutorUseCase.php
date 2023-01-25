<?php

namespace App\Core\UseCase\Executor;

use App\Core\Entity\NextExecutor;
use App\Core\Entity\Profile;
use App\Core\Service\Executor\NextExecutorService\GetSwipedNextExecutorService;

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

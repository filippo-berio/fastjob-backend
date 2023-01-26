<?php

namespace App\Core\Application\UseCase\Executor;

use App\Core\Domain\Entity\NextExecutor;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Service\Executor\NextExecutorService\GetSuggestedNextExecutorService;

class SuggestNextExecutorUseCase
{
    public function __construct(
        private GetSuggestedNextExecutorService $getSuggestedNextExecutorService,
    ) {
    }

    public function suggest(Profile $profile): ?NextExecutor
    {
        return $this->getSuggestedNextExecutorService->get($profile);
    }
}

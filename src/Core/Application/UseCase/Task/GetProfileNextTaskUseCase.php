<?php

namespace App\Core\Application\UseCase\Task;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Service\Task\NextTask\GetProfileNextTaskService;

class GetProfileNextTaskUseCase
{
    public function __construct(
        private GetProfileNextTaskService $getProfileNextTasksService,
    ) {
    }

    /**
     * @param Profile $profile
     * @return Task[]
     */
    public function get(Profile $profile): array
    {
        return $this->getProfileNextTasksService->get($profile);
    }
}

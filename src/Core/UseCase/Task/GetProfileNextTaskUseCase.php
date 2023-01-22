<?php

namespace App\Core\UseCase\Task;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Service\Task\NextTask\GetProfileNextTasksService;

class GetProfileNextTaskUseCase
{
    public function __construct(
        private GetProfileNextTasksService $getProfileNextTasksService,
    ) {
    }

    /**
     * @return Task[]
     */
    public function get(Profile $profile, int $count = 1): array
    {
        return $this->getProfileNextTasksService->get($profile, $count);
    }
}

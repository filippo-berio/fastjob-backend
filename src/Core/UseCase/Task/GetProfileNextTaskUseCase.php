<?php

namespace App\Core\UseCase\Task;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Service\Task\NextTask\GetProfileNextTaskService;

class GetProfileNextTaskUseCase
{
    public function __construct(
        private GetProfileNextTaskService $getProfileNextTasksService,
    ) {
    }

    public function get(Profile $profile): ?Task
    {
        return $this->getProfileNextTasksService->get($profile);
    }
}

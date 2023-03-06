<?php

namespace App\Tests\Acceptance\Core\UseCase\Task;

use App\Core\Application\UseCase\Task\GetExecutorTasksUseCase;
use App\DataFixtures\Core\ProfileFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class GetExecutorTasksTest extends AcceptanceTest
{
    public function testWorkTasksShowUp()
    {
        $useCase = $this->getDependency(GetExecutorTasksUseCase::class);
        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_16);
        $tasks = $useCase->get($profile);
    }
}

<?php

namespace App\Tests\Acceptance\Core\UseCase\Task;

use App\Core\Application\UseCase\Task\GetExecutorTasksUseCase;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class GetExecutorTasksTest extends AcceptanceTest
{
    public function testWorkTasksShowUp()
    {
        $useCase = $this->getDependency(GetExecutorTasksUseCase::class);
        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_16);
        $tasks = $useCase->get($profile);
        $this->assertNotEmpty($tasks);
    }

    public function testFinishedTasks()
    {
        $useCase = $this->getDependency(GetExecutorTasksUseCase::class);
        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_16);
        $tasks = $useCase->get($profile);

        $this->assertCount(2, $tasks->finished);

        $task18 = $tasks->finished[0];
        $task20 = $tasks->finished[1];

        $this->assertEquals(TaskFixtures::TASK_18, $task18->data->getId());
        $this->assertEquals(TaskFixtures::TASK_20, $task20->data->getId());

        $this->assertNull($task18->review);
        $this->assertEquals(5, $task20->review->getRating());
    }
}

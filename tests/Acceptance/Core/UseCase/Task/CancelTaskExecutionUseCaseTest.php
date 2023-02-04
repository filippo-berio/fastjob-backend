<?php

namespace App\Tests\Acceptance\Core\UseCase\Task;

use App\Core\Application\UseCase\Task\CancelTaskExecutionUseCase;
use App\Core\Infrastructure\Entity\ExecutionCancel;
use App\Core\Infrastructure\Entity\Profile;
use App\Core\Infrastructure\Entity\Task;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class CancelTaskExecutionUseCaseTest extends AcceptanceTest
{
    public function testCancelByExecutor()
    {
        $useCase = $this->getDependency(CancelTaskExecutionUseCase::class);
        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_16);
        $useCase->cancel($profile, TaskFixtures::TASK_17);
        $task = $this->getEntity(Task::class, TaskFixtures::TASK_17);
        $this->assertTrue($task->isWait());
        $cancel = $this->getCancel();
        $this->assertFalse($cancel->isForced());
    }

    public function testCancelByAuthor()
    {
        $useCase = $this->getDependency(CancelTaskExecutionUseCase::class);
        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_17);
        $useCase->cancel($profile, TaskFixtures::TASK_17);
        $task = $this->getEntity(Task::class, TaskFixtures::TASK_17);
        $this->assertTrue($task->isWait());
        $cancel = $this->getCancel();
        $this->assertTrue($cancel->isForced());
    }

    private function getCancel(): ExecutionCancel
    {
        return $this->getEntityBy(ExecutionCancel::class, [
            'executor' => $this->getCoreProfile(ProfileFixtures::PROFILE_16),
            'task' => $this->getEntity(Task::class, TaskFixtures::TASK_17),
        ])[0];
    }
}

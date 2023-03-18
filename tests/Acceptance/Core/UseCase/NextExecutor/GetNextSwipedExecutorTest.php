<?php

namespace App\Tests\Acceptance\Core\UseCase\NextExecutor;

use App\Core\Application\UseCase\Executor\GetSwipedNextExecutorUseCase;
use App\Core\Application\Exception\Task\TaskNotFoundException;
use App\Core\Domain\Entity\TaskSwipe;
use App\Core\Domain\Exception\Task\TaskUnavailableToSwipe;
use App\Core\Infrastructure\Entity\Profile;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class GetNextSwipedExecutorTest extends AcceptanceTest
{
    public function testNoWaitTasks()
    {
        $useCase = $this->getDependency(GetSwipedNextExecutorUseCase::class);
        $profile = $this->getEntity(Profile::class, ProfileFixtures::PROFILE_8);
        $this->expectException(TaskUnavailableToSwipe::class);
        $useCase->get($profile, TaskFixtures::TASK_2);
    }

    public function testProfileIsNotAuthor()
    {
        $useCase = $this->getDependency(GetSwipedNextExecutorUseCase::class);
        $profile = $this->getEntity(Profile::class, ProfileFixtures::PROFILE_8);
        $this->expectException(TaskNotFoundException::class);
        $useCase->get($profile, TaskFixtures::TASK_3);
    }

    /**
     * @dataProvider successData
     */
    public function testSuccess(int $authorId, int $taskId, array $expected)
    {
        $useCase = $this->getDependency(GetSwipedNextExecutorUseCase::class);
        $profile = $this->getEntity(Profile::class, $authorId);

        $nextExecutors = $useCase->get($profile, $taskId);
        $this->assertEquals($expected, array_map(
            fn(TaskSwipe $swipe) => $swipe->getProfile()->getId(),
            $nextExecutors
        ));
    }

    /**
     * @dataProvider noExecutorData
     */
    public function testNoExecutor(int $authorId, int $taskId)
    {
        $useCase = $this->getDependency(GetSwipedNextExecutorUseCase::class);
        $profile = $this->getEntity(Profile::class, $authorId);

        $nextExecutors = $useCase->get($profile, $taskId);
        $this->assertEmpty($nextExecutors);
    }

    private function successData()
    {
        return [
            [
                ProfileFixtures::PROFILE_2,
                TaskFixtures::TASK_7,
                [
                    ProfileFixtures::PROFILE_8,
                    ProfileFixtures::PROFILE_10,
                ]
            ],
            [
                ProfileFixtures::PROFILE_2,
                TaskFixtures::TASK_3,
                [
                    ProfileFixtures::PROFILE_10,
                    ProfileFixtures::PROFILE_7,
                ]
            ],
        ];
    }

    private function noExecutorData()
    {
        return [
            // testNoTaskSwipes
            [
                ProfileFixtures::PROFILE_4,
                TaskFixtures::TASK_13
            ],
            // testSwipeTypeRejected
            [
                ProfileFixtures::PROFILE_7,
                TaskFixtures::TASK_10
            ],
        ];
    }
}

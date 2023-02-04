<?php

namespace App\Tests\Acceptance\Core\UseCase\NextExecutor;

use App\Core\Application\UseCase\Executor\GetSwipedNextExecutorUseCase;
use App\Core\Application\UseCase\Swipe\CreateExecutorSwipeUseCase;
use App\Core\Application\Exception\Task\TaskNotFoundException;
use App\Core\Domain\Service\Executor\NextExecutorService\GetSwipedNextExecutorService;
use App\Core\Infrastructure\Entity\Profile;
use App\Core\Domain\Entity\Swipe;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class GetNextSwipedExecutorTest extends AcceptanceTest
{
    public function testNoWaitTasks()
    {
        $useCase = $this->getDependency(GetSwipedNextExecutorUseCase::class);
        $profile = $this->getEntity(Profile::class, ProfileFixtures::PROFILE_8);
        $this->expectException(TaskNotFoundException::class);
        $useCase->get($profile);
    }

    /**
     * @dataProvider successData
     */
    public function testSuccess(int $authorId, array $expected)
    {
        $createExecutorSwipeUseCase = $this->getDependency(CreateExecutorSwipeUseCase::class);
        $useCase = $this->getDependency(GetSwipedNextExecutorUseCase::class);
        $profile = $this->getEntity(Profile::class, $authorId);

        $nextExecutor = $useCase->get($profile);

        foreach ($expected as [$expectedTask, $expectedExecutor]) {
            $this->assertEquals($expectedTask, $nextExecutor->getTask()->getId());
            $this->assertEquals($expectedExecutor, $nextExecutor->getExecutor()->getId());
            $nextExecutor = $createExecutorSwipeUseCase->create(
                $profile,
                $nextExecutor->getTask()->getId(),
                $nextExecutor->getExecutor()->getId(),
                Swipe::TYPE_ACCEPT,
                GetSwipedNextExecutorService::TYPE,
            );
        }
    }

    /**
     * @dataProvider noExecutorData
     */
    public function testNoExecutor(int $authorId)
    {
        $useCase = $this->getDependency(GetSwipedNextExecutorUseCase::class);
        $profile = $this->getEntity(Profile::class, $authorId);

        $nextExecutor = $useCase->get($profile);
        $this->assertNull($nextExecutor);
    }

    private function successData()
    {
        return [
            [
                ProfileFixtures::PROFILE_2,
                [
                    [TaskFixtures::TASK_7, ProfileFixtures::PROFILE_8],
                    [TaskFixtures::TASK_3, ProfileFixtures::PROFILE_10],
                    [TaskFixtures::TASK_3, ProfileFixtures::PROFILE_7],
                    [TaskFixtures::TASK_7, ProfileFixtures::PROFILE_10],
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
            ],
            // testSwipeTypeRejected
            [
                ProfileFixtures::PROFILE_7,
            ],
        ];
    }
}

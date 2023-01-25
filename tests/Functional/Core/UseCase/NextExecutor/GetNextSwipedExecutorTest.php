<?php

namespace App\Tests\Functional\Core\UseCase\NextExecutor;

use App\Core\Entity\Profile;
use App\Core\Entity\Swipe;
use App\Core\UseCase\Executor\GetSwipedNextExecutorUseCase;
use App\Core\UseCase\Swipe\CreateExecutorSwipeUseCase;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Functional\FunctionalTest;

class GetNextSwipedExecutorTest extends FunctionalTest
{
    /**
     * @dataProvider successData
     */
    public function testSuccess(int $authorId, array $expected)
    {
        $createExecutorSwipeUseCase = $this->getDependency(CreateExecutorSwipeUseCase::class);
        $useCase = $this->getDependency(GetSwipedNextExecutorUseCase::class);
        $profile = $this->getEntity(Profile::class, $authorId);

        foreach ($expected as [$expectedTask, $expectedExecutor]) {
            $nextExecutor = $useCase->get($profile);
            $this->assertEquals($expectedTask, $nextExecutor->getTask()->getId());
            $this->assertEquals($expectedExecutor, $nextExecutor->getExecutor()->getId());
            $createExecutorSwipeUseCase->create(
                $profile,
                $nextExecutor->getTask()->getId(),
                $nextExecutor->getExecutor()->getId(),
                Swipe::TYPE_ACCEPT,
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
            // testTaskArchived
            [
                ProfileFixtures::PROFILE_8,
            ],
            // testSwipeTypeRejected
            [
                ProfileFixtures::PROFILE_7,
            ],
        ];
    }
}

<?php

namespace App\Tests\Functional\Core\UseCase\NextTask;

use App\Core\Entity\Profile;
use App\Core\Entity\Swipe;
use App\Core\Entity\Task;
use App\Core\Message\Task\GenerateNextTaskMessage;
use App\Core\Repository\ProfileNextTaskRepository;
use App\Core\UseCase\Swipe\CreateTaskSwipeUseCase;
use App\Core\UseCase\Task\GetProfileNextTaskUseCase;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Functional\FunctionalTest;

class GetProfileNextTasksTest extends FunctionalTest
{
    private const MINIMAL_STACK = 2;
    private const STACK_LIMIT = 3;

    // таски 1 -> 4 -> 5 -> 11 -> 12
    public function testStackLimitsRespected()
    {
        $profile = $this->getEntity(Profile::class, ProfileFixtures::PROFILE_5);
        $useCase = $this->getDependency(GetProfileNextTaskUseCase::class);
        $nextTaskRepo = $this->getDependency(ProfileNextTaskRepository::class);

        $useCase->get($profile);

        $this->processGenerateNextTaskMessage($profile->getId());
        $this->assertEquals(self::STACK_LIMIT, $nextTaskRepo->count($profile));

        $task1 = $useCase->get($profile);
        $this->assertEquals(TaskFixtures::TASK_1, $task1->getId());

        $task4 = $this->createTaskSwipe($profile->getId(), $task1->getId());
        $this->assertEquals(TaskFixtures::TASK_4, $task4->getId());

        $this->assertEquals(self::MINIMAL_STACK, $nextTaskRepo->count($profile));
        $this->messenger()->queue()->assertContains(GenerateNextTaskMessage::class, 0);

        $task5 = $this->createTaskSwipe($profile->getId(), $task4->getId());
        $this->assertEquals(TaskFixtures::TASK_5, $task5->getId());
        $this->assertEquals(self::MINIMAL_STACK - 1, $nextTaskRepo->count($profile));

        $this->processGenerateNextTaskMessage($profile->getId());
        $this->assertEquals(2, $nextTaskRepo->count($profile));

        $task11 = $this->createTaskSwipe($profile->getId(), $task5->getId());
        $this->assertEquals(TaskFixtures::TASK_11, $task11->getId());

        $this->assertEquals(1, $nextTaskRepo->count($profile));
        $this->processGenerateNextTaskMessage($profile->getId());
        $this->assertEquals(1, $nextTaskRepo->count($profile));

        $task12 = $this->createTaskSwipe($profile->getId(), $task11->getId());
        $this->assertEquals(TaskFixtures::TASK_12, $task12->getId());

        $this->processGenerateNextTaskMessage($profile->getId());

        $this->assertEquals(0, $nextTaskRepo->count($profile));

        $nextTask = $this->createTaskSwipe($profile->getId(), $task12->getId());
        $this->assertNull($nextTask);
    }

    /**
     * @dataProvider successData
     */
    public function testNextTaskGenerateCorrectly(int $profileId, array $expectedTasks)
    {
        $profile = $this->getEntity(Profile::class, $profileId);
        $useCase = $this->getDependency(GetProfileNextTaskUseCase::class);

        $task = $useCase->get($profile);
        $this->assertEquals($expectedTasks[0], $task->getId());

        $this->processGenerateNextTaskMessage($profileId);

        $task = $useCase->get($profile);
        $this->assertEquals($expectedTasks[0], $task->getId());

        for ($i = 1; $i < count($expectedTasks); $i++) {
            $task = $this->createTaskSwipe($profileId, $task->getId());
            $this->assertEquals($expectedTasks[$i], $task->getId());
        }

        $task = $this->createTaskSwipe($profileId, $task->getId());
        $this->assertNull($task);
    }

    private function processGenerateNextTaskMessage(int $profileId)
    {
        $this->messenger()->queue()->assertContains(GenerateNextTaskMessage::class, 1);
        /** @var GenerateNextTaskMessage $message */
        $message = $this->messenger()->queue()->first(GenerateNextTaskMessage::class)->getMessage();
        $this->assertEquals($profileId, $message->profileId);
        $this->assertEquals(self::STACK_LIMIT, $message->count);
        $this->messenger()->process();
    }

    private function createTaskSwipe(int $profileId, int $taskId): ?Task
    {
        $swipeUseCase = $this->getDependency(CreateTaskSwipeUseCase::class);
        $profile = $this->getEntity(Profile::class, $profileId);
        return $swipeUseCase->create($profile, $taskId, Swipe::TYPE_REJECT);
    }

    private function successData()
    {
        return [
            [
                ProfileFixtures::PROFILE_7,
                [
                    TaskFixtures::TASK_6,
                    TaskFixtures::TASK_7,
                ],
            ],
            [
                ProfileFixtures::PROFILE_5,
                [
                    TaskFixtures::TASK_1,
                    TaskFixtures::TASK_4,
                    TaskFixtures::TASK_5,
                    TaskFixtures::TASK_11,
                    TaskFixtures::TASK_12,
                ],
            ],
        ];
    }
}
<?php

namespace App\Tests\Acceptance\Core\UseCase\NextTask;

use App\Core\Application\UseCase\Swipe\CreateTaskSwipeUseCase;
use App\Core\Application\UseCase\Task\GetProfileNextTaskUseCase;
use App\Core\Domain\Entity\Swipe;
use App\Core\Domain\Event\Task\GenerateNext\GenerateNextTaskEvent;
use App\Core\Domain\Repository\ProfileNextTaskRepositoryInterface;
use App\Core\Infrastructure\Entity\Profile;
use App\Core\Infrastructure\Entity\Task;
use App\Core\Infrastructure\Message\Event\EventMessage;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class GetProfileNextTasksTest extends AcceptanceTest
{
    private const STACK_LIMIT = 3;

    // таски 1 -> 4 -> 5 -> 11 -> 12
    public function testStackLimitsRespected()
    {
        $profile = $this->getEntity(Profile::class, ProfileFixtures::PROFILE_5);
        $useCase = $this->getDependency(GetProfileNextTaskUseCase::class);
        $nextTaskRepo = $this->getDependency(ProfileNextTaskRepositoryInterface::class);

        $nextTasks = $useCase->get($profile);
        $this->assertCount(self::STACK_LIMIT, $nextTasks);

        $this->processGenerateNextTaskMessage($profile->getId());

        $this->assertEquals(2, $nextTaskRepo->count($profile));

        $nextTasks = $useCase->get($profile);
        $this->assertEquals(TaskFixtures::TASK_1, $nextTasks[0]->getId());

        $nextTasks = $this->createTaskSwipe($profile->getId(), TaskFixtures::TASK_1);
        $this->assertEquals(TaskFixtures::TASK_4, $nextTasks[0]->getId());

        $this->assertEquals(2, $nextTaskRepo->count($profile));
        $this->messenger()->queue()->assertContains(EventMessage::class, 0);

        $nextTasks = $this->createTaskSwipe($profile->getId(), TaskFixtures::TASK_4);
        $this->assertEquals(TaskFixtures::TASK_5, $nextTasks[0]->getId());

        $nextTasks = $this->createTaskSwipe($profile->getId(), TaskFixtures::TASK_5);
        $this->assertEquals(TaskFixtures::TASK_11, $nextTasks[0]->getId());

        $this->assertEquals(0, $nextTaskRepo->count($profile));
        $this->processGenerateNextTaskMessage($profile->getId());
        $this->assertEquals(0, $nextTaskRepo->count($profile));

        $nextTasks = $this->createTaskSwipe($profile->getId(), TaskFixtures::TASK_11);
        $this->assertEquals(TaskFixtures::TASK_12, $nextTasks[0]->getId());

        $nextTasks = $this->createTaskSwipe($profile->getId(), TaskFixtures::TASK_12);
        $this->assertEmpty($nextTasks);
    }

    /**
     * @dataProvider successData
     */
    public function testNextTaskGenerateCorrectly(int $profileId, array $expectedTasks)
    {
        $profile = $this->getEntity(Profile::class, $profileId);
        $useCase = $this->getDependency(GetProfileNextTaskUseCase::class);

        $nextTasks = $useCase->get($profile);
        $this->assertEquals($expectedTasks, array_map(
            fn(Task $task) => $task->getId(),
            $nextTasks
        ));
    }

    private function processGenerateNextTaskMessage(int $profileId)
    {
        $this->messenger()->queue()->assertContains(EventMessage::class, 1);
        /** @var EventMessage $message */
        $message = $this->messenger()->queue()->first(EventMessage::class)->getMessage();
        /** @var GenerateNextTaskEvent $event */
        $event = $message->event;
        $this->assertInstanceOf(GenerateNextTaskEvent::class, $message->event);
        $this->assertEquals($profileId, $event->profileId);
        $this->assertEquals(self::STACK_LIMIT, $event->count);
        $this->messenger()->process();
    }

    /**
     * @param int $profileId
     * @param int $taskId
     * @return Task[]
     */
    private function createTaskSwipe(int $profileId, int $taskId): array
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
            ]
        ];
    }
}

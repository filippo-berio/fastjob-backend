<?php

namespace App\Tests\Functional\Core\UseCase\NextTask;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Exception\Task\NextTaskCountExceedsLimitException;
use App\Core\Message\Task\GenerateNextTaskMessage;
use App\Core\Repository\ProfileNextTaskRepository;
use App\Core\UseCase\Task\GetProfileNextTaskUseCase;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Functional\FunctionalTest;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

class GetProfileNextTasksTest extends FunctionalTest
{
    use InteractsWithMessenger;

    /**
     * @dataProvider successData
     */
    public function testSuccess(int $profileId, int $count, array $expected)
    {
        $this->bootContainer();
        $profile = $this->getEntity(Profile::class, $profileId);
        $useCase = $this->getDependency(GetProfileNextTaskUseCase::class);
        $tasks = $useCase->get($profile, $count);

        $this->assertEquals($expected, array_map(
            fn(Task $task) => $task->getId(),
            $tasks
        ));

        $this->messenger()->queue()->assertContains(GenerateNextTaskMessage::class, 1);
        /** @var GenerateNextTaskMessage $message */
        $message = $this->messenger()->queue()->first(GenerateNextTaskMessage::class)->getMessage();
        $this->assertEquals(ProfileNextTaskRepository::STACK_LIMIT - count($expected), $message->count);
        $this->assertEquals($profileId, $message->profileId);
    }

    public function testCountExceedsLimit()
    {
        $this->bootContainer();
        $profile = $this->getEntity(Profile::class, ProfileFixtures::PROFILE_1);
        $useCase = $this->getDependency(GetProfileNextTaskUseCase::class);
        $this->expectException(NextTaskCountExceedsLimitException::class);
        $useCase->get($profile, 20);
    }

    private function successData()
    {
        return [
            [
                ProfileFixtures::PROFILE_5,
                2,
                [
                    TaskFixtures::TASK_1,
                    TaskFixtures::TASK_4,
                ],
            ],
            [
                ProfileFixtures::PROFILE_7,
                5,
                [
                    TaskFixtures::TASK_6,
                    TaskFixtures::TASK_7,
                ],
            ],
        ];
    }
}

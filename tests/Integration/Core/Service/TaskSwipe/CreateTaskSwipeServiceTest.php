<?php

namespace App\Tests\Integration\Core\Service\TaskSwipe;

use App\Core\Entity\Profile;
use App\Core\Entity\Swipe;
use App\Core\Entity\Task;
use App\Core\Entity\TaskSwipe;
use App\Core\Exception\TaskSwipe\TaskSwipeExistsException;
use App\Core\Service\TaskSwipe\CreateTaskSwipeService;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Integration\IntegrationTest;
use Doctrine\ORM\EntityManagerInterface;

class CreateTaskSwipeServiceTest extends IntegrationTest
{
    /**
     * @dataProvider errorData
     */
    public function testError(
        string $exception,
        int    $profileId,
        int    $taskId,
        string $type,
        ?int   $customPrice = null
    ) {
        $this->bootContainer();

        $em = $this->getEm();
        $profile = $em->getRepository(Profile::class)->find($profileId);
        $task = $em->getRepository(Task::class)->find($taskId);

        $this->expectException($exception);
        $service = $this->getService();
        $service->create($profile, $task, $type, $customPrice);
    }

    /**
     * @dataProvider successData
     */
    public function testSuccess(
        int    $profileId,
        int    $taskId,
        string $type,
        ?int   $customPrice = null
    ) {
        $this->bootContainer();

        $em = $this->getEm();
        $repo = $em->getRepository(TaskSwipe::class);
        $before = $repo->findAll();

        $profile = $em->getRepository(Profile::class)->find($profileId);
        $task = $em->getRepository(Task::class)->find($taskId);
        $service = $this->getService();
        $taskSwipe = $service->create($profile, $task, $type, $customPrice);

        $after = $repo->findAll();
        $this->assertCount(count($before) + 1, $after);

        if ($taskSwipe->getType() === Swipe::TYPE_REJECT) {
            $this->assertNull($taskSwipe->getCustomPrice());
        }
    }

    private function successData()
    {
        return [
            [
                ProfileFixtures::PROFILE_1,
                TaskFixtures::TASK_3,
                Swipe::TYPE_ACCEPT,
            ],
            [
                ProfileFixtures::PROFILE_1,
                TaskFixtures::TASK_1,
                Swipe::TYPE_ACCEPT,
                1500,
            ],
            [
                ProfileFixtures::PROFILE_1,
                TaskFixtures::TASK_1,
                Swipe::TYPE_REJECT,
            ],
            [
                ProfileFixtures::PROFILE_1,
                TaskFixtures::TASK_1,
                Swipe::TYPE_REJECT,
                1500,
            ],
        ];
    }

    private function errorData(): array
    {
        return [
            [
                TaskSwipeExistsException::class,
                ProfileFixtures::PROFILE_2,
                TaskFixtures::TASK_3,
                Swipe::TYPE_REJECT
            ],
            [
                TaskSwipeExistsException::class,
                ProfileFixtures::PROFILE_2,
                TaskFixtures::TASK_3,
                Swipe::TYPE_ACCEPT,
                1500
            ],
            [
                TaskSwipeExistsException::class,
                ProfileFixtures::PROFILE_2,
                TaskFixtures::TASK_3,
                Swipe::TYPE_ACCEPT,
            ],
        ];
    }

    private function getService(): CreateTaskSwipeService
    {
        return $this->getDependency(CreateTaskSwipeService::class);
    }

    private function getEm(): EntityManagerInterface
    {
        return $this->getDependency(EntityManagerInterface::class);
    }
}

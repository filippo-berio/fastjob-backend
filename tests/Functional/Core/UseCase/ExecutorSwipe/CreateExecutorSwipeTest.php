<?php

namespace App\Tests\Functional\Core\UseCase\ExecutorSwipe;

use App\Auth\Entity\User;
use App\Core\Entity\ExecutorSwipe;
use App\Core\Entity\Profile;
use App\Core\Entity\Swipe;
use App\Core\Exception\ExecutorSwipe\ExecutorSwipeExistsException;
use App\Core\Exception\ExecutorSwipe\ExecutorSwipeSelfAssignException;
use App\Core\Exception\Profile\ProfileNotFoundException;
use App\Core\Exception\Task\TaskNotFoundException;
use App\Core\UseCase\Swipe\CreateExecutorSwipeUseCase;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Functional\FunctionalTest;
use Doctrine\ORM\EntityManagerInterface;

class CreateExecutorSwipeTest extends FunctionalTest
{
    /**
     * @dataProvider errorData
     */
    public function testError(
        string $exception,
        int    $profileId,
        int    $taskId,
        int    $executorId,
        string $type
    ) {
        $this->bootContainer();

        $useCase = $this->getUseCase();
        $this->expectException($exception);

        $profile = $this->getEntity(Profile::class, $profileId);
        $useCase->create($profile, $taskId, $executorId,  $type);
    }
    /**
     * @dataProvider successData
     */
    public function testSuccess(
        int $profileId,
        int $taskId,
        int $executorId,
        string $type
    ) {
        $this->bootContainer();

        $em = $this->getEm();
        $repo = $em->getRepository(ExecutorSwipe::class);
        $before = $repo->findAll();

        $useCase = $this->getUseCase();

        $profile = $this->getEntity(Profile::class, $profileId);
        $useCase->create($profile, $taskId, $executorId,  $type);

        $after = $repo->findAll();
        $this->assertCount(count($before) + 1, $after);
    }

    private function successData()
    {
        return [
            // нет совпадения по категориям
            [
                ProfileFixtures::PROFILE_3,
                TaskFixtures::TASK_1,
                ProfileFixtures::PROFILE_4,
                Swipe::TYPE_ACCEPT,
            ],
            [
                ProfileFixtures::PROFILE_3,
                TaskFixtures::TASK_1,
                ProfileFixtures::PROFILE_4,
                Swipe::TYPE_REJECT,
            ],
            // есть совпадение по категориям
            [
                ProfileFixtures::PROFILE_3,
                TaskFixtures::TASK_1,
                ProfileFixtures::PROFILE_5,
                Swipe::TYPE_ACCEPT,
            ],
        ];
    }

    private function errorData()
    {
        return [
            [
                TaskNotFoundException::class,
                ProfileFixtures::PROFILE_2,
                TaskFixtures::TASK_1,
                ProfileFixtures::PROFILE_4,
                Swipe::TYPE_ACCEPT,
            ],
            [
                TaskNotFoundException::class,
                ProfileFixtures::PROFILE_2,
                1000,
                ProfileFixtures::PROFILE_4,
                Swipe::TYPE_ACCEPT,
            ],
            [
                ExecutorSwipeSelfAssignException::class,
                ProfileFixtures::PROFILE_3,
                TaskFixtures::TASK_1,
                ProfileFixtures::PROFILE_3,
                Swipe::TYPE_ACCEPT,
            ],
            [
                ProfileNotFoundException::class,
                ProfileFixtures::PROFILE_3,
                TaskFixtures::TASK_1,
                1000,
                Swipe::TYPE_ACCEPT,
            ],
            [
                ExecutorSwipeExistsException::class,
                ProfileFixtures::PROFILE_2,
                TaskFixtures::TASK_3,
                ProfileFixtures::PROFILE_4,
                Swipe::TYPE_REJECT,
            ],
        ];
    }

    private function getUseCase(): CreateExecutorSwipeUseCase
    {
        return $this->getDependency(CreateExecutorSwipeUseCase::class);
    }

    private function getEm(): EntityManagerInterface
    {
        return $this->getDependency(EntityManagerInterface::class);
    }
}

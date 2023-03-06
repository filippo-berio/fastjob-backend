<?php

namespace App\Tests\Acceptance\Core\UseCase\Task;

use App\Core\Application\UseCase\Task\OfferTaskUseCase;
use App\Core\Infrastructure\Entity\Task;
use App\Core\Domain\Exception\SwipeMatch\SwipeMatchNotFoundException;
use App\Core\Domain\Exception\TaskOffer\TaskOfferExistsException;
use App\Core\Domain\Repository\TaskOfferRepositoryInterface;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class OfferTaskUseCaseTest extends AcceptanceTest
{
    /**
     * @dataProvider errorData
     */
    public function testError(string $exception, int $profileId, int $taskId, int $executorId)
    {
        $useCase = $this->getDependency(OfferTaskUseCase::class);
        $profile = $this->getCoreProfile($profileId);
        $this->expectException($exception);
        $useCase->offer($profile, $taskId, $executorId);
    }

    public function testSuccess()
    {
        $useCase = $this->getDependency(OfferTaskUseCase::class);
        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_11);
        $task = $this->getEntity(Task::class, TaskFixtures::TASK_14);
        $executor = $this->getCoreProfile(ProfileFixtures::PROFILE_13);
        $useCase->offer($profile, $task->getId(), $executor->getId());

        $repo = $this->getDependency(TaskOfferRepositoryInterface::class);
        $offer = $repo->findByProfileAndTask($executor, $task);
        $this->assertNotNull($offer);
        $this->assertEquals($task->getId(), $offer->getTask()->getId());
        $this->assertEquals($executor->getId(), $offer->getProfile()->getId());
    }

    private function errorData()
    {
        return [
            [
                TaskOfferExistsException::class,
                ProfileFixtures::PROFILE_15,
                TaskFixtures::TASK_16,
                ProfileFixtures::PROFILE_16,
            ],
            [
                SwipeMatchNotFoundException::class,
                ProfileFixtures::PROFILE_2,
                TaskFixtures::TASK_7,
                ProfileFixtures::PROFILE_5,
            ],
        ];
    }
}

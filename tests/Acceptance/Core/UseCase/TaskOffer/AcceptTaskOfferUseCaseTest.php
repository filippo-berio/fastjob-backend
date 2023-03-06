<?php

namespace App\Tests\Acceptance\Core\UseCase\TaskOffer;

use App\Core\Application\UseCase\TaskOffer\AcceptOfferUseCase;
use App\Core\Domain\Exception\TaskOffer\TaskOfferNotFoundException;
use App\Core\Domain\Repository\TaskOfferRepositoryInterface;
use App\Core\Infrastructure\Entity\Task;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class AcceptTaskOfferUseCaseTest extends AcceptanceTest
{
    public function testSuccess()
    {
        $task = $this->getEntity(Task::class, TaskFixtures::TASK_16);
// TODO выпилить и протестить актуальные юзкейсы
//        $executorSwipeMatchesUseCase = $this->getDependency(GetExecutorMatchesUseCase::class);

        $useCase = $this->getDependency(AcceptOfferUseCase::class);
//        $matches = $executorSwipeMatchesUseCase->get($this->getCoreProfile(ProfileFixtures::PROFILE_17));
//        $this->assertNotEmpty($matches);

        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_16);
        $useCase->acceptOffer($profile, $task->getId());
        $repo = $this->getDependency(TaskOfferRepositoryInterface::class);
        $offer = $repo->findByProfileAndTask($profile, $task);
        $this->assertNotNull($offer);
        $this->assertEquals($task->getId(), $offer->getTask()->getId());
        $this->assertEquals($profile->getId(), $offer->getProfile()->getId());

//        $matches = $executorSwipeMatchesUseCase->get($this->getCoreProfile(ProfileFixtures::PROFILE_17));
//        $this->assertEmpty($matches);
    }

    /**
     * @dataProvider errorData
     */
    public function testError(string $exception, int $profileId, int $taskId)
    {
        $useCase = $this->getDependency(AcceptOfferUseCase::class);
        $profile = $this->getCoreProfile($profileId);
        $this->expectException($exception);
        $useCase->acceptOffer($profile, $taskId);
    }

    private function errorData()
    {
        return [
            [
                TaskOfferNotFoundException::class,
                ProfileFixtures::PROFILE_16,
                TaskFixtures::NOT_EXIST_TASK,
            ],
            [
                TaskOfferNotFoundException::class,
                ProfileFixtures::PROFILE_16,
                TaskFixtures::TASK_15,
            ],
        ];
    }
}

<?php

namespace App\Tests\Functional\Core\UseCase\Task;

use App\Core\Application\UseCase\Task\OfferTaskUseCase;
use App\Core\Domain\Exception\SwipeMatch\SwipeMatchNotFoundException;
use App\Core\Domain\Exception\TaskOffer\TaskOfferExistsException;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Functional\FunctionalTest;

class OfferTaskUseCaseTest extends FunctionalTest
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

    private function errorData()
    {
        return [
            [
                SwipeMatchNotFoundException::class,
                ProfileFixtures::PROFILE_2,
                TaskFixtures::TASK_7,
                ProfileFixtures::PROFILE_5,
            ],
            [
                TaskOfferExistsException::class,
                ProfileFixtures::PROFILE_15,
                TaskFixtures::TASK_16,
                ProfileFixtures::PROFILE_16,
            ],
            [
                TaskOfferExistsException::class,
                ProfileFixtures::PROFILE_15,
                TaskFixtures::TASK_16,
                ProfileFixtures::PROFILE_17,
            ],
        ];
    }
}

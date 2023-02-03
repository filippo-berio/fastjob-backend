<?php

namespace App\Tests\Functional\Core\UseCase\Review;

use App\Core\Application\UseCase\Review\GetAvailableReviewTargetsUseCase;
use App\Core\Domain\Entity\Task;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Functional\FunctionalTest;

class GetAvailableReviewTargetsTest extends FunctionalTest
{
    /**
     * @dataProvider data
     */
    public function testSuccess(int $profileId, array $expected = [])
    {
        $profile = $this->getCoreProfile($profileId);
        $tasks = $this->getDependency(GetAvailableReviewTargetsUseCase::class)->get($profile);
        $actual = array_map(
            fn(Task $task) => $task->getId(),
            $tasks
        );
        sort($actual);
        $this->assertEquals($expected, $actual);
    }

    private function data()
    {
        return [
            [
                ProfileFixtures::PROFILE_16,
                [
                    TaskFixtures::TASK_18,
                ]
            ],
        ];
    }
}

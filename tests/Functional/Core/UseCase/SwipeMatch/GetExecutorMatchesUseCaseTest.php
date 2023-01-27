<?php

namespace App\Tests\Functional\Core\UseCase\SwipeMatch;

use App\Core\Application\UseCase\SwipeMatch\GetExecutorMatchesUseCase;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Functional\FunctionalTest;

class GetExecutorMatchesUseCaseTest extends FunctionalTest
{
    /**
     * @dataProvider data
     */
    public function testSuccess(int $profileId, array $expected)
    {
        $useCase = $this->getDependency(GetExecutorMatchesUseCase::class);
        $profile = $this->getCoreProfile($profileId);
        $matches = $useCase->get($profile);
        foreach ($expected as $index => $match) {
            $actualMatch = $matches[$index];
            $this->assertEquals($match[0], $actualMatch->getTask()->getId());
            $this->assertEquals($profileId, $actualMatch->getExecutor()->getId());
            $this->assertEquals($match[1] ?? null, $actualMatch->getCustomPrice());
        }
    }

    private function data()
    {
        return [
            [
                ProfileFixtures::PROFILE_4,
                [
                    [TaskFixtures::TASK_3, 300],
                ],
            ],
        ];
    }
}

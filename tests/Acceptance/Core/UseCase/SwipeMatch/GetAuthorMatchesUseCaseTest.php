<?php

namespace App\Tests\Acceptance\Core\UseCase\SwipeMatch;

use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Acceptance\AcceptanceTest;

// TODO выпилить и протестить актуальные юзкейсы
class GetAuthorMatchesUseCaseTest
{
//    public function testCorrectMatchesShown()
//    {
//        $useCase = $this->getDependency(GetAuthorMatchesUseCase::class);
//        $author = $this->getCoreProfile(ProfileFixtures::PROFILE_11);
//        $authorMatches = $useCase->get($author);
//        $expected = $this->getExpectedTaskProfileMap();
//
//        foreach ($authorMatches as $taskMatches) {
//            $profiles = $expected[$taskMatches->task->getId()];
//            $this->assertCount(count($profiles), $taskMatches->matches);
//            foreach ($taskMatches->matches as $match) {
//                $profileId = $match->getExecutor()->getId();
//                $this->assertArrayHasKey($profileId, $profiles);
//                $this->assertEquals($profiles[$profileId], $match->getCustomPrice());
//            }
//        }
//    }

    private function getExpectedTaskProfileMap()
    {
        return [
            TaskFixtures::TASK_14 => [
                ProfileFixtures::PROFILE_13 => null,
                ProfileFixtures::PROFILE_14 => 2000,
            ],
            TaskFixtures::TASK_15 => [
                ProfileFixtures::PROFILE_13 => null,
                ProfileFixtures::PROFILE_15 => null,
            ],
        ];
    }
}

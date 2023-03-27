<?php

namespace App\Tests\Acceptance\Core\UseCase\Chat;

use App\Core\Application\UseCase\Chat\GetChatUseCase;
use App\Core\Domain\Exception\SwipeMatch\SwipeMatchNotFoundException;
use App\DataFixtures\Chat\DirectChatFixtures;
use App\DataFixtures\Core\ProfileFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class GetChatTest extends AcceptanceTest
{
    public function testChatDidExist()
    {
        $useCase = $this->getDependency(GetChatUseCase::class);
        $profile15 = $this->getCoreProfile(ProfileFixtures::PROFILE_15);
        $profile16 = $this->getCoreProfile(ProfileFixtures::PROFILE_16);

        $chat = $useCase->get($profile15, $profile16->getId());
        $this->assertEquals(DirectChatFixtures::CHAT_1, $chat->getId());

        // меняем порядок чуваков
        $chat = $useCase->get($profile16, $profile15->getId());
        $this->assertEquals(DirectChatFixtures::CHAT_1, $chat->getId());

        $this->assertCount(2, $chat->getMessages());
        foreach ($chat->getMessages() as $message) {
            $this->assertTrue($message->isRead());
        }
    }

    public function testChatDidNotExist()
    {
        $useCase = $this->getDependency(GetChatUseCase::class);
        $profile15 = $this->getCoreProfile(ProfileFixtures::PROFILE_15);
        $profile16 = $this->getCoreProfile(ProfileFixtures::PROFILE_17);
        $chat = $useCase->get($profile15, $profile16->getId());
        $this->assertNotNull($chat->getId());
   }

    /**
     * @dataProvider noMatchData
     */
    public function testNoMatch(int $profileA, int $profileB)
    {
        $useCase = $this->getDependency(GetChatUseCase::class);
        $profileA = $this->getCoreProfile($profileA);
        $this->expectException(SwipeMatchNotFoundException::class);
        $useCase->get($profileA, $profileB);
    }

    public function noMatchData()
    {
        return [
            // автор свайпнул вправо
            [
                ProfileFixtures::PROFILE_11,
                ProfileFixtures::PROFILE_7
            ],
            // никто не свайпал
            [
                ProfileFixtures::PROFILE_10,
                ProfileFixtures::PROFILE_14
            ],
        ];
    }
}

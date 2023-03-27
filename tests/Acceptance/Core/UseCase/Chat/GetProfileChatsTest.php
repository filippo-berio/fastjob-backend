<?php

namespace App\Tests\Acceptance\Core\UseCase\Chat;

use App\Core\Application\UseCase\Chat\GetProfileChatsUseCase;
use App\DataFixtures\Chat\DirectChatFixtures;
use App\DataFixtures\Core\ProfileFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class GetProfileChatsTest extends AcceptanceTest
{
    public function testProfileHasChat()
    {
        $useCase = $this->getDependency(GetProfileChatsUseCase::class);
        $profile15 = $this->getCoreProfile(ProfileFixtures::PROFILE_15);
        $profile16 = $this->getCoreProfile(ProfileFixtures::PROFILE_16);

        $profile1Chats = $useCase->get($profile15);
        $this->assertEquals(DirectChatFixtures::CHAT_1, $profile1Chats[0]->chatId);

        $profile2Chats = $useCase->get($profile16);
        $this->assertEquals(DirectChatFixtures::CHAT_1, $profile2Chats[0]->chatId);
    }

    public function testProfileHasNoChats()
    {
        $useCase = $this->getDependency(GetProfileChatsUseCase::class);
        $profile1 = $this->getCoreProfile(ProfileFixtures::PROFILE_1);
        $chats = $useCase->get($profile1);
        $this->assertEmpty($chats);
    }
}

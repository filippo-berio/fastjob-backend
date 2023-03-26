<?php

namespace App\Tests\Acceptance\Core\UseCase\Chat;

use App\Chat\Entity\DirectMessage;
use App\Core\Application\UseCase\Chat\CreateMessageUseCase;
use App\Core\Application\UseCase\Chat\GetChatUseCase;
use App\DataFixtures\Chat\DirectChatFixtures;
use App\DataFixtures\Core\ProfileFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class CreateMessageTest extends AcceptanceTest
{
    /** @dataProvider messageData */
    public function testSuccess(string $content)
    {
        $getChatUseCase = $this->getDependency(GetChatUseCase::class);
        $useCase = $this->getDependency(CreateMessageUseCase::class);
        $profile15 = $this->getCoreProfile(ProfileFixtures::PROFILE_15);

        $useCase->create($profile15, DirectChatFixtures::CHAT_1, 'Тест!');

        $chat = $getChatUseCase->get($profile15, ProfileFixtures::PROFILE_16);
        $messages = $chat->getMessages();
        $this->assertCount(3, $messages);
        /** @var DirectMessage $lastMessage */
        $lastMessage = array_pop($messages);
        $this->assertEquals($content, $lastMessage->getContent());
    }

    private function messageData()
    {
        return [
            [
                'Тест!'
            ]
        ];
    }
}

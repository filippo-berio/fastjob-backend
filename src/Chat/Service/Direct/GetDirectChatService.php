<?php

namespace App\Chat\Service\Direct;

use App\Chat\Entity\DirectChat;
use App\Chat\Entity\PersonInterface;
use App\Chat\Repository\DirectChatRepositoryInterface;
use App\Chat\Repository\DirectMessageRepositoryInterface;

class GetDirectChatService
{
    public function __construct(
        private DirectChatRepositoryInterface $directChatRepository,
        private DirectMessageRepositoryInterface $directMessageRepository,
    ) {
    }

    public function getOrCreate(PersonInterface $user, PersonInterface $companion): DirectChat
    {
        $chat = $this->directChatRepository->getForCompanions($user, $companion);
        if (!$chat) {
            $chat = new DirectChat($user, $companion);
            $this->directChatRepository->save($chat);
        }
        foreach ($chat->getMessages() as $message) {
            $message->read();
            $this->directMessageRepository->save($message);
        }
        return $chat;
    }
}

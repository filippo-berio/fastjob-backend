<?php

namespace App\Chat\Service\Direct;

use App\Chat\DTO\UserChatListItem;
use App\Chat\Entity\DirectChat;
use App\Chat\Entity\PersonInterface;
use App\Chat\Repository\DirectChatRepositoryInterface;

class GetPersonChatsService
{
    public function __construct(
        private DirectChatRepositoryInterface $directChatRepository,
    ) {
    }

    /**
     * @param PersonInterface $person
     * @return UserChatListItem[]
     */
    public function get(PersonInterface $person): array
    {
        $chats = $this->directChatRepository->getForPerson($person);
        return array_map(function (DirectChat $chat) use ($person) {
            $unreadCount = 0;
            foreach ($chat->getMessages() as $message) {
                if (!$message->isRead() && $message->getAuthor()->getId() === $person->getId()) {
                    $unreadCount++;
                }
            }

            $messages = $chat->getMessages();

            return new UserChatListItem(
                $chat->getId(),
                $chat->getCompanionOf($person),
                array_shift($messages),
                $unreadCount,
            );
        }, $chats);
    }
}

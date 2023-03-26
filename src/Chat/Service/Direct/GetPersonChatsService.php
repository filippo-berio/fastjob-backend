<?php

namespace App\Chat\Service\Direct;

use App\Chat\DTO\UserChat;
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
     * @return UserChat[]
     */
    public function get(PersonInterface $person): array
    {
        $chats = $this->directChatRepository->getForPerson($person);
        return array_map(fn(DirectChat $chat) => new UserChat($chat), $chats);
    }
}

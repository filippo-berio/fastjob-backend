<?php

namespace App\Chat\DTO;

use App\Chat\Entity\DirectMessage;
use App\Chat\Entity\PersonInterface;
use JsonSerializable;

readonly class UserChatListItem implements JsonSerializable
{
    public function __construct(
        public int             $chatId,
        public PersonInterface $companion,
        public DirectMessage   $lastMessage,
        public int             $unreadCount,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'chatId' => $this->chatId,
            'companion' => $this->companion,
            'unreadCount' => $this->unreadCount,
            'lastMessage' => $this->lastMessage,
        ];
    }
}

<?php

namespace App\Chat\DTO;

use App\Chat\Entity\DirectChat;

readonly class UserChat
{
    public int $unreadCount;

    public function __construct(
        public DirectChat $chat,
    ) {
        $this->unreadCount = 1;
    }

}

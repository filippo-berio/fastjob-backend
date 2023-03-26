<?php

namespace App\Chat\Service\Direct;

use App\Chat\Entity\DirectChat;
use App\Chat\Entity\DirectMessage;
use App\Chat\Entity\PersonInterface;
use App\Chat\Repository\DirectMessageRepositoryInterface;

class CreateMessageService
{
    public function __construct(
        private DirectMessageRepositoryInterface $directMessageRepository,
    ) {
    }

    public function create(PersonInterface $person, DirectChat $chat, string $content)
    {
        $message = new DirectMessage($chat, $content, $person);
        $this->directMessageRepository->save($message);
    }
}

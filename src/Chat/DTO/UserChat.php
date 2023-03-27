<?php

namespace App\Chat\DTO;

use App\Chat\Entity\DirectChat;
use App\Chat\Entity\DirectMessage;
use App\Chat\Entity\PersonInterface;
use JsonSerializable;

readonly class UserChat implements JsonSerializable
{
    public function __construct(
        public int             $chatId,
        public PersonInterface $companion,
        public DirectMessage   $lastMessage,
        public int             $unreadCount,
    ) {
    }

    public static function makeFor(PersonInterface $person, DirectChat $chat): self
    {
        $companion = $person->getId() === $chat->getPersonA()->getId() ?
            $chat->getPersonB() :
            $chat->getPersonA();
        return new self($chat->getId(), $companion, $chat->getMessages()[0], 1);
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

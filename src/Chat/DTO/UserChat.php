<?php

namespace App\Chat\DTO;

use App\Chat\Entity\PersonInterface;
use JsonSerializable;

readonly class UserChat implements JsonSerializable
{
    public function __construct(
        public int $id,
        public array $messages,
        public PersonInterface $companion,
        public PersonInterface $person,
        public int $unreadCount
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'companion' => $this->companion,
            'person' => $this->person,
            'messages' => $this->messages,
            'unreadCount' => $this->unreadCount,
            'id' => $this->id,
        ];
    }
}

<?php

namespace App\Core\Domain\Entity;

use JsonSerializable;

class Review implements JsonSerializable
{
    public function __construct(
        protected Task $task,
        protected Profile $author,
        protected Profile $target,
        protected int $rating,
        protected ?string $comment = null
    ) {
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getTarget(): Profile
    {
        return $this->target;
    }

    public function getAuthor(): Profile
    {
        return $this->author;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function jsonSerialize(): array
    {
        return [
            'rating' => $this->rating,
            'comment' => $this->comment,
            'author' => $this->author,
        ];
    }
}

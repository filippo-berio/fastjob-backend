<?php

namespace App\Review\Domain\Entity;

class Review
{
    protected string $id;

    public function __construct(
        protected Profile $author,
        protected Profile $target,
        protected int $rating,
        protected ?string $comment = null,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
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

}

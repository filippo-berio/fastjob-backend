<?php

namespace App\Review\Domain\Entity;

class ReviewAvailability
{
    protected bool $allowComment;

    public function __construct(
        protected Profile $author,
        protected Profile $target,
    ) {
        $this->allowComment = false;
    }

    public function getAuthor(): Profile
    {
        return $this->author;
    }

    public function getTarget(): Profile
    {
        return $this->target;
    }

    public function setAllowComment(bool $allowComment): void
    {
        $this->allowComment = $allowComment;
    }

    public function isAllowComment(): bool
    {
        return $this->allowComment;
    }
}

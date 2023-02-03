<?php

namespace App\Core\Domain\DTO\Review;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;

readonly class CreateReviewDTO
{
    public function __construct(
        public Task $task,
        public Profile $author,
        public Profile $target,
        public int $rating,
        public ?string $comment = null,
    ) {
    }
}

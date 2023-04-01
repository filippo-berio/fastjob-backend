<?php

namespace App\Core\Domain\DTO\Task;

use App\Core\Domain\Entity\Review;
use App\Core\Domain\Entity\Task;
use JsonSerializable;

class FinishedTask implements JsonSerializable
{
    public function __construct(
        public Task $data,
        public ?Review $review = null,
    ) {
    }

   public function jsonSerialize(): array
   {
       return [
           'data' => $this->data,
           'review' => $this->review,
       ];
   }
}

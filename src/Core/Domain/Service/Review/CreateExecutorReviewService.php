<?php

namespace App\Core\Domain\Service\Review;

use App\Core\Domain\DTO\Review\CreateReviewDTO;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Exception\Review\ReviewUnavailableException;

class CreateExecutorReviewService
{
    public function __construct(
        private CanLeaveReviewService $canLeaveReviewService,
        private CreateReviewService $createReviewService,
    ) {
    }

    public function create(
        Profile $profile,
        Task $task,
        int $rating,
        ?string $comment = null
    ) {
        if (!$this->canLeaveReviewService->can($profile, $task)) {
            throw new ReviewUnavailableException();
        }

        $this->createReviewService->create(new CreateReviewDTO(
            $task,
            $profile,
            $task->getAuthor(),
            $rating,
            $comment
        ));
    }
}

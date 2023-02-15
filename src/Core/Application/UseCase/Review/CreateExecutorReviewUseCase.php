<?php

namespace App\Core\Application\UseCase\Review;

use App\Core\Application\Exception\Task\TaskNotFoundException;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Query\Task\FindTaskById;
use App\Core\Domain\Service\Review\CreateExecutorReviewService;
use App\Lib\CQRS\Bus\QueryBusInterface;

class CreateExecutorReviewUseCase
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CreateExecutorReviewService $createExecutorReviewService,
    ) {
    }

    public function create(
        Profile $profile,
        int $taskId,
        string $rating,
        ?string $comment = null,
    ) {
        $task = $this->queryBus->query(new FindTaskById($taskId));
        if (!$task) {
            throw new TaskNotFoundException();
        }
        $this->createExecutorReviewService->create($profile, $task, $rating, $comment);
    }
}

<?php

namespace App\Core\Domain\Service\Task;

use App\Core\Application\UseCase\Review\CreateReviewService;
use App\Core\Domain\Command\Task\Save\SaveTask;
use App\Core\Domain\DTO\Review\CreateReviewDTO;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Exception\Task\TaskNotInWorkException;
use App\Core\Domain\Query\Profile\GetTaskExecutor;
use App\Lib\CQRS\Bus\CommandBusInterface;
use App\Lib\CQRS\Bus\QueryBusInterface;

class FinishTaskService
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private CreateReviewService $createReviewService,
    ) {
    }

    public function finish(
        Task $task,
        int $rating,
        ?string $reviewComment = null,
    ) {
        if (!$task->isInWork()) {
            throw new TaskNotInWorkException();
        }
        $task->finish();
        $this->commandBus->execute(new SaveTask($task));

        /** @var Profile $executor */
        $executor = $this->queryBus->query(new GetTaskExecutor($task));

        $this->createReviewService->create(new CreateReviewDTO(
            $task,
            $task->getAuthor(),
            $executor,
            $rating,
            $reviewComment
        ));
    }
}

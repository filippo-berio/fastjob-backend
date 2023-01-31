<?php

namespace App\Core\Domain\Service\Task;

use App\Core\Domain\Command\Task\Save\SaveTask;
use App\Core\Domain\Contract\EntityMapperInterface;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Review;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Exception\Task\TaskNotInWorkException;
use App\Core\Domain\Query\Profile\GetTaskExecutor;
use App\Core\Domain\Repository\ReviewRepositoryInterface;
use App\CQRS\Bus\CommandBusInterface;
use App\CQRS\Bus\QueryBusInterface;

class FinishTaskService
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private ReviewRepositoryInterface $reviewRepository,
        private EntityMapperInterface $entityMapper,
        private QueryBusInterface $queryBus,
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
        $reviewEntity = $this->entityMapper->persistenceEntity(Review::class);
        $review = new $reviewEntity(
            $task,
            $task->getAuthor(),
            $executor,
            $rating,
            $reviewComment
        );
        $this->reviewRepository->save($review);
    }
}

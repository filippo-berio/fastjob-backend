<?php

namespace App\Core\Domain\Service\Task;

use App\Core\Domain\Command\Task\Save\SaveTask;
use App\Core\Domain\Contract\EntityMapperInterface;
use App\Core\Domain\Entity\ExecutionCancel;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Exception\Task\TaskCancelException;
use App\Core\Domain\Query\Profile\GetTaskExecutor;
use App\Core\Domain\Repository\ExecutionCancelRepositoryInterface;
use App\CQRS\Bus\CommandBusInterface;
use App\CQRS\Bus\QueryBusInterface;

class CancelTaskExecutionService
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CommandBusInterface $commandBus,
        private EntityMapperInterface $entityMapper,
        private ExecutionCancelRepositoryInterface $executionCancelRepository,
    ) {
    }

    public function cancel(Profile $profile, Task $task)
    {
        /** @var Profile $executor */
        $executor = $this->queryBus->query(new GetTaskExecutor($task));

        $cancelledByAuthor = $task->getAuthor()->getId() === $profile->getId();
        if (!$cancelledByAuthor) {
            if ($executor->getId() !== $profile->getId()) {
                throw new TaskCancelException();
            }
        }

        if (!$task->isInWork()) {
            throw new TaskCancelException();
        }

        $task->resetStatus();
        $this->commandBus->execute(new SaveTask($task));

        $cancellationEntity = $this->entityMapper->persistenceEntity(ExecutionCancel::class);
        $cancellation = new $cancellationEntity(
            $executor,
            $task,
            $cancelledByAuthor
        );
        $this->executionCancelRepository->save($cancellation);
    }
}

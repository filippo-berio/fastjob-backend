<?php

namespace App\Core\Domain\Service\Task;

use App\Core\Domain\Command\Task\Save\SaveTask;
use App\Core\Domain\Contract\EntityMapperInterface;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Entity\TaskOffer;
use App\Core\Domain\Exception\SwipeMatch\SwipeMatchNotFoundException;
use App\Core\Domain\Exception\TaskOffer\TaskOfferExistsException;
use App\Core\Domain\Repository\SwipeMatchRepositoryInterface;
use App\Core\Domain\Repository\TaskOfferRepositoryInterface;
use App\CQRS\Bus\CommandBusInterface;

class OfferTaskService
{
    public function __construct(
        private TaskOfferRepositoryInterface $taskOfferRepository,
        private EntityMapperInterface $entityMapper,
        private SwipeMatchRepositoryInterface $swipeMatchRepository,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function offer(Task $task, Profile $executor): TaskOffer
    {
        if ($task->isOffered()) {
            throw new TaskOfferExistsException();
        }

        if (!$this->swipeMatchRepository->findByTaskAndExecutor($task, $executor)) {
            throw new SwipeMatchNotFoundException();
        }

        if ($this->taskOfferRepository->findByProfileAndTask($executor, $task)) {
            throw new TaskOfferExistsException();
        }

        $entity = $this->entityMapper->persistenceEntity(TaskOffer::class);
        $taskOffer = new $entity($task, $executor);
        $taskOffer = $this->taskOfferRepository->save($taskOffer);

        $task->offer();
        $this->commandBus->execute(new SaveTask($task));

        return $taskOffer;
    }
}

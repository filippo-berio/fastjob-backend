<?php

namespace App\Core\Domain\Event\Task\Offer;

use App\Core\Domain\Contract\EntityMapperInterface;
use App\Core\Domain\Entity\TaskOffer;
use App\Core\Domain\Event\EventHandlerInterface;
use App\Core\Domain\Event\EventInterface;
use App\Core\Domain\Exception\SwipeMatch\SwipeMatchNotFoundException;
use App\Core\Domain\Exception\TaskOffer\TaskOfferExistsException;
use App\Core\Domain\Repository\SwipeMatchRepositoryInterface;
use App\Core\Domain\Repository\TaskOfferRepositoryInterface;

class TaskOfferEventHandler implements EventHandlerInterface
{
    public function __construct(
        private TaskOfferRepositoryInterface $taskOfferRepository,
        private EntityMapperInterface $entityMapper,
        private SwipeMatchRepositoryInterface $swipeMatchRepository,
    ) {
    }

    public function executionType(): string
    {
        return self::EXECUTION_TYPE_NOW;
    }

    public function event(): string
    {
        return TaskOfferEvent::class;
    }

    /**
     * @param TaskOfferEvent $event
     * @return void
     */
    public function handle(EventInterface $event)
    {
        if (!$this->swipeMatchRepository->findByTaskAndExecutor($event->task, $event->executor)) {
            throw new SwipeMatchNotFoundException();
        }

        if ($this->taskOfferRepository->findByProfileAndTask($event->executor, $event->task)) {
            throw new TaskOfferExistsException();
        }

        $entity = $this->entityMapper->persistenceEntity(TaskOffer::class);
        $taskOffer = new $entity($event->task, $event->executor);
        $this->taskOfferRepository->save($taskOffer);
    }
}

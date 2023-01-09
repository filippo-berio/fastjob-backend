<?php

namespace App\Core\Service\TaskResponse;

use App\Core\Data\Command\TaskResponse\SaveTaskResponse;
use App\Core\Data\Query\TaskResponse\FindByProfileTask;
use App\Core\Entity\TaskResponse;
use App\Core\Event\TaskResponse\UserRespondedEvent;
use App\Core\Exception\TaskResponse\TaskResponseExistsException;
use App\CQRS\Bus\CommandBusInterface;
use App\CQRS\Bus\QueryBusInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SaveTaskResponseService
{
    public function __construct(
        private QueryBusInterface        $queryBus,
        private CommandBusInterface      $commandBus,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function save(TaskResponse $taskResponse)
    {
        if ($this->queryBus->handle(new FindByProfileTask($taskResponse->getTask(), $taskResponse->getResponder()))) {
            throw new TaskResponseExistsException();
        }
        /** @var TaskResponse $taskResponse */
        $taskResponse = $this->commandBus->handle(new SaveTaskResponse($taskResponse));
        $this->eventDispatcher->dispatch(new UserRespondedEvent($taskResponse->getId()));
    }
}

<?php

namespace App\Core\Infrastructure\Event\Listener\EntityLifecycle;

use App\Core\Domain\Event\EventDispatcherInterface;
use App\Core\Domain\Query\Profile\GetTaskExecutor;
use App\Core\Domain\Repository\SwipeMatchRepositoryInterface;
use App\Core\Infrastructure\Entity\Task;
use App\Lib\CQRS\Bus\QueryBusInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsEntityListener(event: Events::postLoad, entity: Task::class)]
class TaskPostLoadListener
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private QueryBusInterface $queryBus,
        protected SwipeMatchRepositoryInterface $matchRepository,
    ) {
    }

    public function __invoke(Task $task, LifecycleEventArgs $args)
    {
        $task->setEventDispatcher($this->eventDispatcher);
        if (in_array($task->getStatus(), Task::EXECUTOR_STATUSES)) {
            $executor = $this->queryBus->query(new GetTaskExecutor($task));
            $task->setExecutor($executor);
        }
        $matches = $this->matchRepository->findForTask($task);
        $task->setMatches($matches);
    }
}

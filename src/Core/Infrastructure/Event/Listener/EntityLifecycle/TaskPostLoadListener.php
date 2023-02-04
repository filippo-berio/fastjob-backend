<?php

namespace App\Core\Infrastructure\Event\Listener\EntityLifecycle;

use App\Core\Domain\Event\EventDispatcherInterface;
use App\Core\Infrastructure\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsEntityListener(event: Events::postLoad, entity: Task::class)]
class TaskPostLoadListener
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(Task $task, LifecycleEventArgs $args)
    {
        $task->setEventDispatcher($this->eventDispatcher);
    }
}

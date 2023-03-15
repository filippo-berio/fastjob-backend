<?php

namespace App\Core\Infrastructure\Event\Listener\EntityLifecycle\Task;

use App\Core\Infrastructure\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postPersist, entity: Task::class)]
class TaskPostPersistListener
{
    public function __construct(
        private string $storageEndpoint,
    ) {
    }

    public function __invoke(Task $task)
    {
        $task->removePhotoPrefix($this->storageEndpoint);
    }
}

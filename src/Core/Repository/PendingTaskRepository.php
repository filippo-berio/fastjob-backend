<?php

namespace App\Core\Repository;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Query\Task\FindById\FindTaskById;
use App\CQRS\Bus\QueryBusInterface;
use Predis\Client;

# таски, не свайпнутые исполнителями
class PendingTaskRepository
{
    public function __construct(
        private Client $redis,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function set(Profile $profile, Task $task)
    {
        $this->redis->set('pending-task:' . $profile->getId(), $task->getId());
    }

    public function get(Profile $profile): ?Task
    {
        $id = $this->redis->get('pending-task:' . $profile->getId());
        return $id ? $this->queryBus->query(new FindTaskById($id)) : null;
    }

    public function clear(Profile $profile)
    {
        $this->redis->del('pending-task:' . $profile->getId());
    }
}

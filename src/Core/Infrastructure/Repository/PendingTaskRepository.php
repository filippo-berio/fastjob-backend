<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Query\Task\FindTaskById;
use App\Core\Domain\Repository\PendingTaskRepositoryInterface;
use App\CQRS\Bus\QueryBusInterface;
use Predis\Client;

# таски, не свайпнутые исполнителями
class PendingTaskRepository implements PendingTaskRepositoryInterface
{
    private Client $redis;

    public function __construct(
        private QueryBusInterface $queryBus,
        string $redisHost,
    ) {
        $this->redis = new Client($redisHost);
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

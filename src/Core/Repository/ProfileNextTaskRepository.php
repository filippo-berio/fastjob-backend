<?php

namespace App\Core\Repository;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Query\Task\FindById\FindTaskById;
use App\Core\Query\Task\FindByIds\FindTaskByIds;
use App\CQRS\Bus\QueryBusInterface;
use Predis\Client;

class ProfileNextTaskRepository
{
    public function __construct(
        private Client $redis,
        private QueryBusInterface $queryBus,
    ) {
    }

    /**
     * @param Profile $profile
     * @param Task[] $tasks
     */
    public function add(Profile $profile, array $tasks)
    {
        $this->redis->lpush(
            'profile:next-tasks:' . $profile->getId(),
            array_map(
                fn(Task $task) => $task->getId(),
                $tasks
            )
        );
    }

    /**
     * @param Profile $profile
     * @return Task[]
     */
    public function get(Profile $profile): array
    {
        $ids = $this->redis->lrange('profile:next-tasks:' . $profile->getId(), 0, $this->count($profile) - 1);
        return !empty($ids) ? $this->queryBus->query(new FindTaskByIds($ids)) : [];
    }

    public function pop(Profile $profile): ?Task
    {
        $id = $this->redis->rpop('profile:next-tasks:' . $profile->getId());
        return $id ? $this->queryBus->query(new FindTaskById($id)) : null;
    }

    public function count(Profile $profile): int
    {
        return $this->redis->llen('profile:next-tasks:' . $profile->getId());
    }
}

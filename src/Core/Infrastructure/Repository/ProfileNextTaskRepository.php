<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Query\Task\FindTaskById;
use App\Core\Domain\Query\Task\FindTaskByIds;
use App\Core\Domain\Repository\ProfileNextTaskRepositoryInterface;
use App\Lib\CQRS\Bus\QueryBusInterface;
use Predis\Client;

class ProfileNextTaskRepository implements ProfileNextTaskRepositoryInterface
{
    private Client $redis;

    public function __construct(
        private QueryBusInterface $queryBus,
        string $redisHost,
    ) {
        $this->redis = new Client($redisHost);
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

    public function clear(Profile $profile)
    {
        $this->redis->del('profile:next-tasks:' . $profile->getId());
    }
}

<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Query\Task\FindTaskById;
use App\Core\Domain\Query\Task\FindTaskByIds;
use App\Lib\CQRS\Bus\QueryBusInterface;
use Predis\Client;

abstract class BaseTaskStackRepository
{
    private Client $redis;

    public function __construct(
        private QueryBusInterface $queryBus,
        string $redisHost,
    ) {
        $this->redis = new Client($redisHost);
    }

    protected abstract function getKey(Profile $profile): string;

    /**
     * @param Profile $profile
     * @param Task[] $tasks
     */
    public function push(Profile $profile, array $tasks)
    {
        $this->redis->lpush(
            $this->getKey($profile),
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
        $ids = $this->redis->lrange($this->getKey($profile), 0, $this->count($profile) - 1);
        return !empty($ids) ? $this->queryBus->query(new FindTaskByIds($ids)) : [];
    }

    /**
     * @param Profile $profile
     * @return Task[]
     */
    public function popAll(Profile $profile): array
    {
        $ids = $this->get($profile);
        $this->clear($profile);
        return $ids ? $this->queryBus->query(new FindTaskByIds($ids)) : [];
    }

    public function pop(Profile $profile): ?Task
    {
        $id = $this->redis->rpop($this->getKey($profile));
        return $id ? $this->queryBus->query(new FindTaskById($id)) : null;
    }

    public function count(Profile $profile): int
    {
        return $this->redis->llen($this->getKey($profile));
    }

    public function clear(Profile $profile)
    {
        $this->redis->del($this->getKey($profile));
    }
}

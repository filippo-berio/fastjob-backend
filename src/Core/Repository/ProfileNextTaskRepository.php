<?php

namespace App\Core\Repository;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Query\Task\FindByIds\FindTaskByIds;
use App\Core\Query\Task\FindByIds\FindTaskByIdsHandler;
use Predis\Client;

class ProfileNextTaskRepository
{
    const STACK_LIMIT = 7;

    public function __construct(
        private Client $redis,
        private FindTaskByIdsHandler $findTaskByIds,
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
        $ids[] = $this->redis->lrange('profile:next-tasks:' . $profile->getId(), 0, self::STACK_LIMIT - 1);
        return empty($ids) ? $this->findTaskByIds->handle(new FindTaskByIds($ids)) : [];
    }

    /**
     * @param Profile $profile
     * @param int $count
     * @return Task[]
     */
    public function pop(Profile $profile, int $count = 1): array
    {
        $ids = [];
        for ($i = 0; $i < min($this->count($profile), $count); $i++) {
            $ids[] = $this->redis->rpop('profile:next-tasks:' . $profile->getId());
        }
        return empty($ids) ? $this->findTaskByIds->handle(new FindTaskByIds($ids)) : [];
    }

    public function count(Profile $profile): int
    {
        return $this->redis->llen('profile:next-tasks:' . $profile->getId());
    }
}

<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Domain\Query\Task\FindTaskById;
use App\Core\Infrastructure\Entity\Task;
use App\Lib\CQRS\QueryInterface;

class FindTaskByIdHandler extends BaseTaskQueryHandler
{

    /**
     * @param FindTaskById $query
     * @return Task|null
     */
    public function handle(QueryInterface $query): ?Task
    {
        /** @var FindTaskById $query */
        return $this->entityManager->getRepository(Task::class)->find($query->id);
    }

    public function getQueryClass(): string
    {
        return FindTaskById::class;
    }
}

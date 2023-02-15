<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Domain\Query\Task\FindTaskByIds;
use App\Core\Infrastructure\Entity\Task;
use App\Lib\CQRS\QueryInterface;

class FindTaskByIdsHandler extends BaseTaskQueryHandler
{
    /**
     * @param FindTaskByIds $query
     * @return Task[]
     */
    public function handle(QueryInterface $query): array
    {
        return $this->entityManager->getRepository(Task::class)
            ->createQueryBuilder('t')
            ->andWhere('t.id in (:id)')
            ->setParameter('id', $query->ids)
            ->getQuery()
            ->getResult();

    }

    public function getQueryClass(): string
    {
        return FindTaskByIds::class;
    }
}

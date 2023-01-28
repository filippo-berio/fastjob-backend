<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Domain\Query\Task\FindTaskByAuthorAndId;
use App\Core\Infrastructure\Entity\Task;
use App\CQRS\QueryInterface;

class FindTaskByAuthorAndIdHandler extends BaseTaskQueryHandler
{
    /**
     * @param FindTaskByAuthorAndId $query
     * @return ?Task
     */
    public function handle(QueryInterface $query): ?Task
    {
        return $this->entityManager->getRepository(Task::class)
            ->createQueryBuilder('t')
            ->andWhere('t.author = :author')
            ->setParameter('author', $query->profile)
            ->andWhere('t.id = :id')
            ->setParameter('id', $query->id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getQueryClass(): string
    {
        return FindTaskByAuthorAndId::class;
    }
}

<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Domain\Query\Task\FindTaskByAuthor;
use App\Core\Infrastructure\Entity\Task;
use App\Lib\CQRS\QueryInterface;

class FindTaskByAuthorHandler extends BaseTaskQueryHandler
{
    /**
     * @param FindTaskByAuthor $query
     * @return Task[]
     */
    public function handle(QueryInterface $query): array
    {
        return $this->entityManager->getRepository(Task::class)
            ->createQueryBuilder('t')
            ->andWhere('t.author = :author')
            ->setParameter('author', $query->profile)
            ->getQuery()
            ->getResult();
    }

    public function getQueryClass(): string
    {
        return FindTaskByAuthor::class;
    }
}

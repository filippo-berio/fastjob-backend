<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Domain\Query\Task\FindWaitTaskByAuthor;
use App\Core\Infrastructure\Entity\Task;
use App\Lib\CQRS\QueryInterface;

class FindWaitTaskByAuthorHandler extends BaseTaskQueryHandler
{

    /**
     * @param FindWaitTaskByAuthor $query
     * @return Task[]
     */
    public function handle(QueryInterface $query): array
    {
        $qb = $this->entityManager->getRepository(Task::class)
            ->createQueryBuilder('t')
            ->andWhere('t.author = :author')
            ->setParameter('author', $query->profile);
        $this->filterFreeTaskStatus($qb);
        return $qb
            ->getQuery()
            ->getResult();
    }

    public function getQueryClass(): string
    {
        return FindWaitTaskByAuthor::class;
    }
}

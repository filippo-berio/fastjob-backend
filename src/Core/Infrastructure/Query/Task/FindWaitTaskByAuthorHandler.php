<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Domain\Query\Task\FindWaitTaskByAuthor;
use App\Core\Infrastructure\Entity\Task;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

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
        $this->filterStatus($qb);
        return $qb
            ->getQuery()
            ->getResult();
    }

    public function getQueryClass(): string
    {
        return FindWaitTaskByAuthor::class;
    }
}

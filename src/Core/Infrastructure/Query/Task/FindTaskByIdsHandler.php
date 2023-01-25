<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Domain\Entity\Task;
use App\Core\Domain\Query\Task\FindTaskByIds;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindTaskByIdsHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

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

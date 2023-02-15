<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Infrastructure\Entity\Task;
use App\Core\Infrastructure\Entity\TaskOffer;
use App\Lib\CQRS\QueryHandlerInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

abstract class BaseTaskQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    protected function filterStatus(QueryBuilder $queryBuilder, string $status = Task::STATUS_WAIT): QueryBuilder
    {
        return $queryBuilder
            ->andWhere('t.status = :status')
            ->setParameter('status', $status);
    }

    protected function filterDeadlineNotExpired(QueryBuilder $queryBuilder): QueryBuilder
    {
        return $queryBuilder->andWhere('t.deadline is null or t.deadline > :now')
            ->setParameter('now', new DateTimeImmutable());
    }

    protected function exclude(QueryBuilder $queryBuilder, array $ids): QueryBuilder
    {
        return $queryBuilder->andWhere('t.id not in (:excludeIds)')
            ->setParameter('excludeIds', $ids);
    }

    protected function joinAcceptedTaskOffers(QueryBuilder $queryBuilder): QueryBuilder
    {
        return $queryBuilder
            ->innerJoin(TaskOffer::class, 'to', Join::WITH, 'identity(to.task) = t.id')
            ->andWhere('to.status = :acceptStatus')
            ->setParameter('acceptStatus', TaskOffer::STATUS_ACCEPTED);
    }
}

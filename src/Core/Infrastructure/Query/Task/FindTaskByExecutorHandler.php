<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Domain\Query\Task\FindTaskByExecutor;
use App\Core\Infrastructure\Entity\Task;
use App\Lib\CQRS\QueryInterface;

class FindTaskByExecutorHandler extends BaseTaskQueryHandler
{

    /**
     * @param FindTaskByExecutor $query
     * @return Task[]
     */
    public function handle(QueryInterface $query): array
    {
        $qb = $this->entityManager->getRepository(Task::class)
            ->createQueryBuilder('t');
        $this->joinAcceptedTaskOffers($qb);
        return $qb
            ->andWhere('identity(to.profile) = :profile')
            ->setParameter('profile', $query->executor->getId())
            ->getQuery()
            ->getResult();
    }

    public function getQueryClass(): string
    {
        return FindTaskByExecutor::class;
    }
}

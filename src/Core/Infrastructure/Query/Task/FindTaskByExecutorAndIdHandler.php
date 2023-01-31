<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Infrastructure\Entity\Task;
use App\Core\Domain\Query\Task\FindTaskByExecutorAndId;
use App\CQRS\QueryInterface;

class FindTaskByExecutorAndIdHandler extends BaseTaskQueryHandler
{

    /**
     * @param FindTaskByExecutorAndId $query
     * @return Task
     */
    public function handle(QueryInterface $query): Task
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
        return FindTaskByExecutorAndId::class;
    }
}

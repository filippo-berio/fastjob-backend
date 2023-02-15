<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Domain\Query\Task\FindTaskByExecutorAndId;
use App\Core\Infrastructure\Entity\Task;
use App\Lib\CQRS\QueryInterface;

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
            ->andWhere('t.id = :id')
            ->setParameter('id', $query->id)
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

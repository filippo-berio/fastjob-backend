<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Domain\Query\Task\FindFinishedTaskByExecutor;
use App\Core\Infrastructure\Entity\Task;
use App\Lib\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindFinishedTaskByExecutorHandler extends BaseTaskQueryHandler
{
    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager);
    }


    /**
     * @param FindFinishedTaskByExecutor $query
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
            ->andWhere('t.status = :status')
            ->setParameter('status', Task::STATUS_FINISHED)
            ->getQuery()
            ->getResult();
    }

    public function getQueryClass(): string
    {
        return FindFinishedTaskByExecutor::class;
    }
}

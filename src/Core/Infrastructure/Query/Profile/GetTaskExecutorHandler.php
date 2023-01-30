<?php

namespace App\Core\Infrastructure\Query\Profile;

use App\Core\Domain\Query\Profile\GetTaskExecutor;
use App\Core\Infrastructure\Entity\Profile;
use App\Core\Infrastructure\Entity\Task;
use App\Core\Infrastructure\Entity\TaskOffer;
use App\CQRS\QueryInterface;

class GetTaskExecutorHandler extends BaseProfileQueryHandler
{

    /**
     * @param GetTaskExecutor $query
     * @return ?Profile
     */
    public function handle(QueryInterface $query): ?Profile
    {
        $profile = $this->entityManager->getRepository(TaskOffer::class)
            ->createQueryBuilder('to')
            ->select('to.profile')
            ->andWhere('to.status = :acceptStatus')
            ->setParameter('acceptStatus', TaskOffer::STATUS_ACCEPTED)
            ->innerJoin('to.task', 't')
            ->andWhere('t.id = :task')
            ->setParameter('task', $query->task->getId())
            ->andWhere('t.status != :taskCancelStatus')
            ->setParameter('taskCancelStatus', Task::STATUS_CANCELED)
            ->getQuery()
            ->getOneOrNullResult();
        dd($profile);
    }

    public function getQueryClass(): string
    {
        return GetTaskExecutor::class;
    }
}

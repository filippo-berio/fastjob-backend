<?php

namespace App\Core\Infrastructure\Query\Profile;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Query\Profile\GetTaskExecutor;
use App\Core\Infrastructure\Entity\Task;
use App\Core\Infrastructure\Entity\TaskOffer;
use App\Lib\CQRS\QueryInterface;

class GetTaskExecutorHandler extends BaseProfileQueryHandler
{

    /**
     * @param GetTaskExecutor $query
     * @return ?Profile
     */
    public function handle(QueryInterface $query): ?Profile
    {
        /** @var ?TaskOffer $taskOffer */
        $taskOffer = $this->entityManager->getRepository(TaskOffer::class)
            ->createQueryBuilder('to')
            ->andWhere('to.status = :acceptStatus')
            ->setParameter('acceptStatus', TaskOffer::STATUS_ACCEPTED)
            ->innerJoin('to.task', 't')
            ->andWhere('t.id = :task')
            ->setParameter('task', $query->task->getId())
            ->andWhere('t.status != :taskCancelStatus')
            ->setParameter('taskCancelStatus', Task::STATUS_CANCELED)
            ->getQuery()
            ->getOneOrNullResult();
        return $taskOffer?->getProfile();
    }

    public function getQueryClass(): string
    {
        return GetTaskExecutor::class;
    }
}

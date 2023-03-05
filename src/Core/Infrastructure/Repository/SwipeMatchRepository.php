<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\Profile as DomainProfile;
use App\Core\Domain\Entity\Swipe;
use App\Core\Domain\Entity\SwipeMatch;
use App\Core\Domain\Entity\Task as DomainTask;
use App\Core\Infrastructure\Entity\TaskOffer;
use App\Core\Domain\Repository\SwipeMatchRepositoryInterface;
use App\Core\Infrastructure\Entity\ExecutorSwipe;
use App\Core\Infrastructure\Entity\TaskSwipe;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

class SwipeMatchRepository implements SwipeMatchRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function findForTask(DomainTask $task): array
    {
        $qb = $this->createTaskSwipeQueryBuilder();
        $this->queryTask($qb, $task);

        $taskSwipes = $qb
            ->getQuery()
            ->getResult();

        $result = [];
        /** @var TaskSwipe $taskSwipe */
        foreach ($taskSwipes as $taskSwipe) {
            $result[] = new SwipeMatch($task, $taskSwipe->getProfile(), $taskSwipe->getCustomPrice());
        }
        return $result;
    }

    public function findForExecutor(DomainProfile $profile): array
    {
        $qb = $this->createTaskSwipeQueryBuilder();
        $this->queryExecutor($qb, $profile);
        $qb
            ->leftJoin(TaskOffer::class, 'to', Join::WITH, 'to.task = ts.task')
            ->andWhere('to.id is null or to.status != :acceptOfferStatus or identity(to.profile) = :profileId')
            ->setParameter('acceptOfferStatus', TaskOffer::STATUS_ACCEPTED);

        $taskSwipes = $qb
            ->getQuery()
            ->getResult();

        $result = [];
        /** @var TaskSwipe $taskSwipe */
        foreach ($taskSwipes as $taskSwipe) {
            $result[] = new SwipeMatch($taskSwipe->getTask(), $profile, $taskSwipe->getCustomPrice());
        }
        return $result;
    }

    public function findByTaskAndExecutor(DomainTask $task, DomainProfile $profile): ?SwipeMatch
    {
        $qb = $this->createTaskSwipeQueryBuilder();
        $this->queryTask($qb, $task);
        $this->queryExecutor($qb, $profile);

        /** @var ?TaskSwipe $taskSwipe */
        $taskSwipe = $qb
            ->getQuery()
            ->getOneOrNullResult();

        if (!$taskSwipe) {
            return null;
        }

        return new SwipeMatch($task, $profile, $taskSwipe->getCustomPrice());
    }

    private function queryExecutor(QueryBuilder $queryBuilder, DomainProfile $profile): QueryBuilder
    {
        return $queryBuilder
            ->andWhere('identity(ts.profile) = :profileId')
            ->setParameter('profileId', $profile->getId());
    }

    private function queryTask(QueryBuilder $queryBuilder, DomainTask $task): QueryBuilder
    {
        return $queryBuilder
            ->innerJoin('ts.task', 't')
            ->andWhere('identity(ts.task) = :taskId')
            ->setParameter('taskId', $task->getId())
            ->innerJoin(TaskOffer::class, 'to', Join::WITH, 'identity(to.task) = t.id')
            ->andWhere('to.status = :waitStatus')
            ->setParameter('waitStatus', TaskOffer::STATUS_WAIT);
    }

    private function createTaskSwipeQueryBuilder(): QueryBuilder
    {
        return $this->entityManager->getRepository(TaskSwipe::class)
            ->createQueryBuilder('ts')
            ->innerJoin(ExecutorSwipe::class, 'es', Join::WITH, 'es.task = ts.task and es.profile = ts.profile')
            ->andWhere('ts.type = :accept and es.type = :accept')
            ->setParameter('accept', Swipe::TYPE_ACCEPT);
    }
}

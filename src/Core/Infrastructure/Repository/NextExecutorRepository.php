<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\Swipe;
use App\Core\Domain\Entity\Task as DomainTask;
use App\Core\Domain\Repository\NextExecutorRepositoryInterface;
use App\Core\Infrastructure\Entity\TaskSwipe;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;

class NextExecutorRepository implements NextExecutorRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function nextSwipedExecutor(DomainTask $task, int $limit): array
    {
        $qb = $this->entityManager->getRepository(TaskSwipe::class)
            ->createQueryBuilder('ts')
            ->andWhere('ts.type = :acceptType')
            ->setParameter('acceptType', Swipe::TYPE_ACCEPT)
            ->andWhere('identity(ts.task) = :taskId')
            ->setParameter('taskId', $task->getId())
            ->setMaxResults($limit)
            ->orderBy('ts.id', 'ASC');

        $executorSwipesSql = $this->executorSwipesSql();
        $sql = "select id from task_swipe where (task_id, profile_id) in ($executorSwipesSql)";

        $stmt = $this->entityManager->getConnection()->prepare($sql);
        $stmt->bindValue('taskId', $task->getId(), ParameterType::INTEGER);
        $excludeTaskSwipes = $stmt->executeQuery()->fetchFirstColumn();

        if ($excludeTaskSwipes) {
            $qb
                ->andWhere('ts.id not in (:exclude)')
                ->setParameter('exclude', $excludeTaskSwipes);
        }

        return $qb->getQuery()->getResult();
    }

    private function executorSwipesSql()
    {
        return '
            select es.task_id, es.profile_id from executor_swipe es
            inner join task t on t.id = es.task_id
            where t.id = :taskId
        ';
    }
}

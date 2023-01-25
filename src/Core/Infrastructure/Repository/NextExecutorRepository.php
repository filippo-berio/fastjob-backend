<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\NextExecutor;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Swipe;
use App\Core\Infrastructure\Entity\Task;
use App\Core\Infrastructure\Entity\TaskSwipe;
use App\Core\Domain\Repository\NextExecutorRepositoryInterface;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;

class NextExecutorRepository implements NextExecutorRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function nextSwipedExecutor(Profile $author): ?NextExecutor
    {
        $taskSwipe = $this->getNextTaskSwipe($author);
        if (!$taskSwipe) {
            return null;
        }
        return new NextExecutor(
            $taskSwipe->getTask(),
            $taskSwipe->getProfile(),
            $taskSwipe
        );
    }

    private function getNextTaskSwipe(Profile $author): ?TaskSwipe
    {
        $qb = $this->entityManager->getRepository(TaskSwipe::class)
            ->createQueryBuilder('ts')
            ->andWhere('ts.type = :acceptType')
            ->setParameter('acceptType', Swipe::TYPE_ACCEPT)

            ->innerJoin('ts.task', 't')
            ->andWhere('t.author = :author')
            ->setParameter('author', $author)
            ->andWhere('t.status = :waitStatus')
            ->setParameter('waitStatus', Task::STATUS_WAIT)

            ->orderBy('ts.id', 'ASC');

        $executorSwipesSql = '
            select es.task_id, es.profile_id from executor_swipe es
            inner join task t on t.id = es.task_id
            where t.author_id = :authorId
        ';
        $sql = "select id from task_swipe where (task_id, profile_id) in ($executorSwipesSql)";

        $stmt = $this->entityManager->getConnection()->prepare($sql);
        $stmt->bindValue('authorId', $author->getId(), ParameterType::INTEGER);
        $excludeTaskSwipes = $stmt->executeQuery()->fetchFirstColumn();

        if ($excludeTaskSwipes) {
            $qb
                ->andWhere('ts.id not in (:exclude)')
                ->setParameter('exclude', $excludeTaskSwipes);
        }

        return $qb->getQuery()->getResult()[0] ?? null;
    }
}

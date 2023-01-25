<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\ExecutorSwipe as DomainExecutorSwipe;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Repository\ExecutorSwipeRepositoryInterface;
use App\Core\Infrastructure\Entity\ExecutorSwipe;
use Doctrine\ORM\EntityManagerInterface;

class ExecutorSwipeRepository implements ExecutorSwipeRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(DomainExecutorSwipe $executorSwipe): DomainExecutorSwipe
    {
        $this->entityManager->persist($executorSwipe);
        $this->entityManager->flush();
        return $executorSwipe;
    }

    public function findByAuthorAndTask(Profile $author, Task $task): ?DomainExecutorSwipe
    {
        return $this->entityManager
            ->getRepository(ExecutorSwipe::class)
            ->createQueryBuilder('s')
            ->andWhere('s.profile = :profile')
            ->andWhere('s.task = :task')
            ->setParameters([
                'task' => $task,
                'profile' => $author,
            ])
            ->getQuery()
            ->getOneOrNullResult();

    }
}

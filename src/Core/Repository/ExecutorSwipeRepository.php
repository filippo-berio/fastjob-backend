<?php

namespace App\Core\Repository;

use App\Core\Entity\ExecutorSwipe;
use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class ExecutorSwipeRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(ExecutorSwipe $executorSwipe): ExecutorSwipe
    {
        $this->entityManager->persist($executorSwipe);
        $this->entityManager->flush();
        return $executorSwipe;
    }

    public function findByAuthorAndTask(Profile $author, Task $task): ?ExecutorSwipe
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

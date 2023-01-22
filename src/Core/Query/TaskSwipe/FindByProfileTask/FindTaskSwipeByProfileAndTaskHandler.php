<?php

namespace App\Core\Query\TaskSwipe\FindByProfileTask;

use App\Core\Entity\TaskSwipe;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindTaskSwipeByProfileAndTaskHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @param FindTaskSwipeByProfileAndTask $query
     * @return TaskSwipe|null
     */
    public function handle(QueryInterface $query): ?TaskSwipe
    {
        return $this->em
            ->getRepository(TaskSwipe::class)
            ->createQueryBuilder('s')
            ->andWhere('s.profile = :profile')
            ->andWhere('s.task = :task')
            ->setParameters([
                'task' => $query->task,
                'profile' => $query->profile
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}

<?php

namespace App\Core\Data\Query\ExecutorSwipe\FindByProfileTask;

use App\Core\Entity\ExecutorSwipe;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindExecutorSwipeByProfileTaskHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(QueryInterface $query): ?ExecutorSwipe
    {
        /** @var FindExecutorSwipeByProfileTask $query */
        return $this->em
            ->getRepository(ExecutorSwipe::class)
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

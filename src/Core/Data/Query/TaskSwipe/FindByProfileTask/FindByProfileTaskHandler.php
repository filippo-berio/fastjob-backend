<?php

namespace App\Core\Data\Query\TaskSwipe\FindByProfileTask;

use App\Core\Entity\TaskSwipe;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindByProfileTaskHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(QueryInterface $query): ?TaskSwipe
    {
        /** @var FindByProfileTask $query */
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
